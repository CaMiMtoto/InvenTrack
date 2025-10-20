<?php

namespace App\Http\Controllers;

use App\Exports\CustomReportExport;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Exceptions\Exception;

class ReportController extends Controller
{
    /**
     * @throws Exception
     */
    public function list()
    {
        if (\request()->ajax()) {
            $source = Report::query();
            return datatables($source)
                ->editColumn('created_at', fn(Report $report) => $report->created_at->toDateTimeString())
                ->addColumn('action', fn(Report $report) => view('admin.reports._actions', compact('report')))
                ->rawColumns(['action'])
                ->make();
        }
        return view('admin.reports.list');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'unique:reports,name'],
            'view_name' => ['required']
        ]);

        $viewName = $data['view_name'];
        $dbName = DB::getDatabaseName();

        $viewExists = DB::selectOne("
    SELECT TABLE_NAME
    FROM INFORMATION_SCHEMA.VIEWS
    WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ?
", [$dbName, $viewName]);
        if (!$viewExists) {
            throw ValidationException::withMessages([
                'view_name' => 'The specified view does not exist in the database.'
            ]);
        }

        $model = Report::create($data);

        if ($request->ajax()) {
            return response()->json([
                'redirect_url' => route('admin.reports.show', encodeId($model->id)),
                'message' => 'Report created successfully.'
            ]);
        }

        return redirect()
            ->route('admin.reports.show', encodeId($model->id))
            ->with('success', 'Report created successfully.');
    }

    public function show(Report $report)
    {
        $columns = Schema::getColumnListing($report->view_name);
        return view('admin.reports.show', compact('report', 'columns'));
    }

    public function generate(Request $request, Report $report)
    {

        $reportName = $request->input('name');
        $selectedColumns = $request->input('columns'); // array
        $sortBy = $request->input('sort_by'); // string
        $sortDir = $request->input('sort_dir', 'asc'); // default to ASC
        // Validate sort column is in selected columns
        if ($sortBy) {
            if (!in_array($sortBy, $selectedColumns)) {
                return back()->withErrors(['sort_by' => 'Invalid sort column']);
            }
        }


        $query = DB::table($report->view_name)->select($selectedColumns);
// Apply dynamic filters
        $filters = $request->input('filters', []);
        foreach ($filters as $filter) {
            if (!empty($filter['column']) && !empty($filter['operator']) && isset($filter['value'])) {
                $column = $filter['column'];
                $operator = $filter['operator'];
                $value = $filter['value'];

                switch ($operator) {
                    case 'like':
                        $query->where($column, 'like', '%' . $value . '%');
                        break;
                    case 'between':
                        if (isset($filter['value2'])) {
                            $query->whereBetween($column, [$value, $filter['value2']]);
                        }
                        break;
                    case 'date_equals':
                        $query->whereDate($column, '=', $value);
                        break;
                    case 'date_before':
                        $query->whereDate($column, '<', $value);
                        break;
                    case 'date_after':
                        $query->whereDate($column, '>', $value);
                        break;
                    default:
                        $query->where($column, $operator, $value);
                        break;
                }
            }
        }
        if ($sortBy) {
            // Validate sort column is in selected columns before applying
            if (in_array($sortBy, $selectedColumns)) {
                $query->orderBy($sortBy, $sortDir);
            }
        }

        $data = $query->get();
        // Pass the original request input to the view for the export form
        $input = $request->all();
        return view('admin.reports.result', compact('report', 'data', 'selectedColumns', 'input'));
//        return view('admin.reports.result', compact('report', 'data', 'selectedColumns'));
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(Request $request, Report $report)
    {
        $selectedColumns = $request->input('columns', []);
        $filters = $request->input('filters', []);
        $sortBy = $request->input('sort_by');
        $sortDir = $request->input('sort_dir', 'asc');

        if (empty($selectedColumns)) {
            return back()->withErrors(['columns' => 'Please select at least one column to export.']);
        }

        $fileName = \Str::slug($report->name) . '-' . now()->format('Y-m-d') . '.xlsx';

        return Excel::download(new CustomReportExport($report->view_name, $selectedColumns, $filters, $sortBy, $sortDir), $fileName);
    }
}
