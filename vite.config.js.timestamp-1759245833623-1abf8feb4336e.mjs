// vite.config.js
import { defineConfig } from "file:///C:/Users/CaMi/PhpstormProjects/InvenTrack/node_modules/vite/dist/node/index.js";
import laravel from "file:///C:/Users/CaMi/PhpstormProjects/InvenTrack/node_modules/laravel-vite-plugin/dist/index.js";
import vue from "file:///C:/Users/CaMi/PhpstormProjects/InvenTrack/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import { ViteImageOptimizer } from "file:///C:/Users/CaMi/PhpstormProjects/InvenTrack/node_modules/vite-plugin-image-optimizer/dist/index.mjs";
import viteImagemin from "file:///C:/Users/CaMi/PhpstormProjects/InvenTrack/node_modules/vite-plugin-imagemin/dist/index.mjs";
var vite_config_default = defineConfig({
  plugins: [
    ViteImageOptimizer({}),
    laravel({
      input: [
        "resources/css/app.css",
        "resources/sass/app.scss",
        "resources/sass/master.scss",
        "resources/js/app.js",
        "resources/js/master.js"
      ],
      refresh: true
    }),
    vue({
      template: {
        transformAssetUrls: {
          base: null,
          includeAbsolute: false
        }
      }
    }),
    viteImagemin({
      gifsicle: {
        optimizationLevel: 7,
        interlaced: false
      },
      optipng: {
        optimizationLevel: 7
      },
      mozjpeg: {
        quality: 70
      },
      pngquant: {
        quality: [0.6, 0.8],
        speed: 1
      },
      svgo: {
        plugins: [
          {
            name: "removeViewBox",
            active: false
          },
          {
            name: "removeEmptyAttrs",
            active: false
          }
        ]
      }
    })
  ],
  resolve: {
    alias: {
      vue: "vue/dist/vue.esm-bundler.js"
    }
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJDOlxcXFxVc2Vyc1xcXFxDYU1pXFxcXFBocHN0b3JtUHJvamVjdHNcXFxcSW52ZW5UcmFja1wiO2NvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9maWxlbmFtZSA9IFwiQzpcXFxcVXNlcnNcXFxcQ2FNaVxcXFxQaHBzdG9ybVByb2plY3RzXFxcXEludmVuVHJhY2tcXFxcdml0ZS5jb25maWcuanNcIjtjb25zdCBfX3ZpdGVfaW5qZWN0ZWRfb3JpZ2luYWxfaW1wb3J0X21ldGFfdXJsID0gXCJmaWxlOi8vL0M6L1VzZXJzL0NhTWkvUGhwc3Rvcm1Qcm9qZWN0cy9JbnZlblRyYWNrL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSAndml0ZSc7XG5pbXBvcnQgbGFyYXZlbCBmcm9tICdsYXJhdmVsLXZpdGUtcGx1Z2luJztcbmltcG9ydCB2dWUgZnJvbSAnQHZpdGVqcy9wbHVnaW4tdnVlJztcbmltcG9ydCB7Vml0ZUltYWdlT3B0aW1pemVyfSBmcm9tIFwidml0ZS1wbHVnaW4taW1hZ2Utb3B0aW1pemVyXCI7XG5pbXBvcnQgdml0ZUltYWdlbWluIGZyb20gJ3ZpdGUtcGx1Z2luLWltYWdlbWluJztcblxuZXhwb3J0IGRlZmF1bHQgZGVmaW5lQ29uZmlnKHtcbiAgICBwbHVnaW5zOiBbXG4gICAgICAgIFZpdGVJbWFnZU9wdGltaXplcih7fSksXG4gICAgICAgIGxhcmF2ZWwoe1xuICAgICAgICAgICAgaW5wdXQ6IFtcbiAgICAgICAgICAgICAgICAncmVzb3VyY2VzL2Nzcy9hcHAuY3NzJyxcbiAgICAgICAgICAgICAgICAncmVzb3VyY2VzL3Nhc3MvYXBwLnNjc3MnLFxuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvc2Fzcy9tYXN0ZXIuc2NzcycsXG4gICAgICAgICAgICAgICAgJ3Jlc291cmNlcy9qcy9hcHAuanMnLFxuICAgICAgICAgICAgICAgICdyZXNvdXJjZXMvanMvbWFzdGVyLmpzJyxcbiAgICAgICAgICAgIF0sXG4gICAgICAgICAgICByZWZyZXNoOiB0cnVlLFxuICAgICAgICB9KSxcbiAgICAgICAgdnVlKHtcbiAgICAgICAgICAgIHRlbXBsYXRlOiB7XG4gICAgICAgICAgICAgICAgdHJhbnNmb3JtQXNzZXRVcmxzOiB7XG4gICAgICAgICAgICAgICAgICAgIGJhc2U6IG51bGwsXG4gICAgICAgICAgICAgICAgICAgIGluY2x1ZGVBYnNvbHV0ZTogZmFsc2UsXG4gICAgICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIH0sXG4gICAgICAgIH0pLFxuICAgICAgICB2aXRlSW1hZ2VtaW4oe1xuICAgICAgICAgICAgZ2lmc2ljbGU6IHtcbiAgICAgICAgICAgICAgICBvcHRpbWl6YXRpb25MZXZlbDogNyxcbiAgICAgICAgICAgICAgICBpbnRlcmxhY2VkOiBmYWxzZSxcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBvcHRpcG5nOiB7XG4gICAgICAgICAgICAgICAgb3B0aW1pemF0aW9uTGV2ZWw6IDcsXG4gICAgICAgICAgICB9LFxuICAgICAgICAgICAgbW96anBlZzoge1xuICAgICAgICAgICAgICAgIHF1YWxpdHk6IDcwLFxuICAgICAgICAgICAgfSxcbiAgICAgICAgICAgIHBuZ3F1YW50OiB7XG4gICAgICAgICAgICAgICAgcXVhbGl0eTogWzAuNiwgMC44XSxcbiAgICAgICAgICAgICAgICBzcGVlZDogMSxcbiAgICAgICAgICAgIH0sXG4gICAgICAgICAgICBzdmdvOiB7XG4gICAgICAgICAgICAgICAgcGx1Z2luczogW1xuICAgICAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgICAgICBuYW1lOiAncmVtb3ZlVmlld0JveCcsXG4gICAgICAgICAgICAgICAgICAgICAgICBhY3RpdmU6IGZhbHNlLFxuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgICAgICB7XG4gICAgICAgICAgICAgICAgICAgICAgICBuYW1lOiAncmVtb3ZlRW1wdHlBdHRycycsXG4gICAgICAgICAgICAgICAgICAgICAgICBhY3RpdmU6IGZhbHNlLFxuICAgICAgICAgICAgICAgICAgICB9LFxuICAgICAgICAgICAgICAgIF0sXG4gICAgICAgICAgICB9LFxuICAgICAgICB9KSxcbiAgICBdLFxuICAgIHJlc29sdmU6IHtcbiAgICAgICAgYWxpYXM6IHtcbiAgICAgICAgICAgIHZ1ZTogJ3Z1ZS9kaXN0L3Z1ZS5lc20tYnVuZGxlci5qcycsXG4gICAgICAgIH0sXG4gICAgfSxcbn0pO1xuIl0sCiAgIm1hcHBpbmdzIjogIjtBQUF1VCxTQUFTLG9CQUFvQjtBQUNwVixPQUFPLGFBQWE7QUFDcEIsT0FBTyxTQUFTO0FBQ2hCLFNBQVEsMEJBQXlCO0FBQ2pDLE9BQU8sa0JBQWtCO0FBRXpCLElBQU8sc0JBQVEsYUFBYTtBQUFBLEVBQ3hCLFNBQVM7QUFBQSxJQUNMLG1CQUFtQixDQUFDLENBQUM7QUFBQSxJQUNyQixRQUFRO0FBQUEsTUFDSixPQUFPO0FBQUEsUUFDSDtBQUFBLFFBQ0E7QUFBQSxRQUNBO0FBQUEsUUFDQTtBQUFBLFFBQ0E7QUFBQSxNQUNKO0FBQUEsTUFDQSxTQUFTO0FBQUEsSUFDYixDQUFDO0FBQUEsSUFDRCxJQUFJO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixvQkFBb0I7QUFBQSxVQUNoQixNQUFNO0FBQUEsVUFDTixpQkFBaUI7QUFBQSxRQUNyQjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxJQUNELGFBQWE7QUFBQSxNQUNULFVBQVU7QUFBQSxRQUNOLG1CQUFtQjtBQUFBLFFBQ25CLFlBQVk7QUFBQSxNQUNoQjtBQUFBLE1BQ0EsU0FBUztBQUFBLFFBQ0wsbUJBQW1CO0FBQUEsTUFDdkI7QUFBQSxNQUNBLFNBQVM7QUFBQSxRQUNMLFNBQVM7QUFBQSxNQUNiO0FBQUEsTUFDQSxVQUFVO0FBQUEsUUFDTixTQUFTLENBQUMsS0FBSyxHQUFHO0FBQUEsUUFDbEIsT0FBTztBQUFBLE1BQ1g7QUFBQSxNQUNBLE1BQU07QUFBQSxRQUNGLFNBQVM7QUFBQSxVQUNMO0FBQUEsWUFDSSxNQUFNO0FBQUEsWUFDTixRQUFRO0FBQUEsVUFDWjtBQUFBLFVBQ0E7QUFBQSxZQUNJLE1BQU07QUFBQSxZQUNOLFFBQVE7QUFBQSxVQUNaO0FBQUEsUUFDSjtBQUFBLE1BQ0o7QUFBQSxJQUNKLENBQUM7QUFBQSxFQUNMO0FBQUEsRUFDQSxTQUFTO0FBQUEsSUFDTCxPQUFPO0FBQUEsTUFDSCxLQUFLO0FBQUEsSUFDVDtBQUFBLEVBQ0o7QUFDSixDQUFDOyIsCiAgIm5hbWVzIjogW10KfQo=
