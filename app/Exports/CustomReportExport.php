<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class CustomReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected string $viewName;
    protected array $selectedColumns;
    protected array $filters;
    protected ?string $sortBy;
    protected ?string $sortDir;

    public function __construct(string $viewName, array $selectedColumns, array $filters, ?string $sortBy, ?string $sortDir)
    {
        $this->viewName = $viewName;
        $this->selectedColumns = $selectedColumns;
        $this->filters = $filters;
        $this->sortBy = $sortBy;
        $this->sortDir = $sortDir;
    }

    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        $query = DB::table($this->viewName)->select($this->selectedColumns);

        // Apply dynamic filters
        foreach ($this->filters as $filter) {
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

        if ($this->sortBy && in_array($this->sortBy, $this->selectedColumns)) {
            $query->orderBy($this->sortBy, $this->sortDir);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Create human-readable headings
        return array_map(function ($column) {
            return ucwords(str_replace('_', ' ', $column));
        }, $this->selectedColumns);
    }

    /**
     * @param mixed $row
     * @return array
     */
    public function map($row): array
    {
        $mappedRow = [];
        foreach ($this->selectedColumns as $column) {
            $mappedRow[] = $row->{$column};
        }
        return $mappedRow;
    }
}
