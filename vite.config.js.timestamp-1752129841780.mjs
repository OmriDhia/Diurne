// vite.config.js
import { defineConfig } from "file:///D:/dev/laragon/www/diurne_vuejs/node_modules/vite/dist/node/index.js";
import vue from "file:///D:/dev/laragon/www/diurne_vuejs/node_modules/@vitejs/plugin-vue/dist/index.mjs";
import path from "path";
import vueI18n from "file:///D:/dev/laragon/www/diurne_vuejs/node_modules/@intlify/vite-plugin-vue-i18n/lib/index.mjs";
import { copy } from "file:///D:/dev/laragon/www/diurne_vuejs/node_modules/vite-plugin-copy/dist/vite-plugin-copy.js";
var __vite_injected_original_dirname = "D:\\dev\\laragon\\www\\diurne_vuejs";
var vite_config_default = defineConfig({
  plugins: [
    vue(),
    vueI18n({
      include: path.resolve(__vite_injected_original_dirname, "./src/locales/**")
    }),
    copy({
      targets: [
        { src: ".htaccess", dest: "dist" }
      ],
      hook: "writeBundle"
    })
  ],
  optimizeDeps: {
    include: ["quill", "nouislider"]
  },
  resolve: {
    alias: [
      {
        find: /^~(.*)$/,
        replacement: "node_modules/$1"
      },
      {
        find: "@",
        replacement: path.resolve(__vite_injected_original_dirname, "./src/")
      }
    ]
  }
});
export {
  vite_config_default as default
};
//# sourceMappingURL=data:application/json;base64,ewogICJ2ZXJzaW9uIjogMywKICAic291cmNlcyI6IFsidml0ZS5jb25maWcuanMiXSwKICAic291cmNlc0NvbnRlbnQiOiBbImNvbnN0IF9fdml0ZV9pbmplY3RlZF9vcmlnaW5hbF9kaXJuYW1lID0gXCJEOlxcXFxkZXZcXFxcbGFyYWdvblxcXFx3d3dcXFxcZGl1cm5lX3Z1ZWpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ZpbGVuYW1lID0gXCJEOlxcXFxkZXZcXFxcbGFyYWdvblxcXFx3d3dcXFxcZGl1cm5lX3Z1ZWpzXFxcXHZpdGUuY29uZmlnLmpzXCI7Y29uc3QgX192aXRlX2luamVjdGVkX29yaWdpbmFsX2ltcG9ydF9tZXRhX3VybCA9IFwiZmlsZTovLy9EOi9kZXYvbGFyYWdvbi93d3cvZGl1cm5lX3Z1ZWpzL3ZpdGUuY29uZmlnLmpzXCI7aW1wb3J0IHsgZGVmaW5lQ29uZmlnIH0gZnJvbSBcInZpdGVcIjtcclxuaW1wb3J0IHZ1ZSBmcm9tIFwiQHZpdGVqcy9wbHVnaW4tdnVlXCI7XHJcbmltcG9ydCBwYXRoIGZyb20gXCJwYXRoXCI7XHJcbmltcG9ydCB2dWVJMThuIGZyb20gXCJAaW50bGlmeS92aXRlLXBsdWdpbi12dWUtaTE4blwiO1xyXG5pbXBvcnQgeyBjb3B5IH0gZnJvbSAndml0ZS1wbHVnaW4tY29weSc7XHJcblxyXG4vLyBodHRwczovL3ZpdGVqcy5kZXYvY29uZmlnL1xyXG5leHBvcnQgZGVmYXVsdCBkZWZpbmVDb25maWcoe1xyXG4gICAgcGx1Z2luczogW1xyXG4gICAgICAgIHZ1ZSgpLFxyXG4gICAgICAgIHZ1ZUkxOG4oe1xyXG4gICAgICAgICAgICBpbmNsdWRlOiBwYXRoLnJlc29sdmUoX19kaXJuYW1lLCBcIi4vc3JjL2xvY2FsZXMvKipcIiksXHJcbiAgICAgICAgfSksXHJcbiAgICAgICAgY29weSh7XHJcbiAgICAgICAgICAgIHRhcmdldHM6IFtcclxuICAgICAgICAgICAgICB7IHNyYzogJy5odGFjY2VzcycsIGRlc3Q6ICdkaXN0JyB9IC8vIEFkanVzdCAnZGlzdCcgdG8geW91ciBidWlsZCBvdXRwdXQgZGlyZWN0b3J5XHJcbiAgICAgICAgICAgIF0sXHJcbiAgICAgICAgICAgIGhvb2s6ICd3cml0ZUJ1bmRsZScgLy8gUnVuIHRoZSBjb3B5IG9wZXJhdGlvbiBhZnRlciB0aGUgYnVpbGQgaXMgY29tcGxldGVcclxuICAgICAgICAgIH0pXHJcbiAgICBdLFxyXG4gICAgb3B0aW1pemVEZXBzOiB7XHJcbiAgICAgICAgaW5jbHVkZTogW1wicXVpbGxcIiwgXCJub3Vpc2xpZGVyXCJdLFxyXG4gICAgfSxcclxuICAgIHJlc29sdmU6IHtcclxuICAgICAgICBhbGlhczogW1xyXG4gICAgICAgICAgICB7XHJcbiAgICAgICAgICAgICAgICBmaW5kOiAvXn4oLiopJC8sXHJcbiAgICAgICAgICAgICAgICByZXBsYWNlbWVudDogXCJub2RlX21vZHVsZXMvJDFcIixcclxuICAgICAgICAgICAgfSxcclxuICAgICAgICAgICAge1xyXG4gICAgICAgICAgICAgICAgZmluZDogXCJAXCIsXHJcbiAgICAgICAgICAgICAgICByZXBsYWNlbWVudDogcGF0aC5yZXNvbHZlKF9fZGlybmFtZSwgXCIuL3NyYy9cIiksIC8vIENvcnJlY3RlZCBmcm9tIFwiLnNyYy9cIiB0byBcIi4vc3JjL1wiXHJcbiAgICAgICAgICAgIH0sXHJcbiAgICAgICAgXSxcclxuICAgIH0sXHJcbn0pO1xyXG4iXSwKICAibWFwcGluZ3MiOiAiO0FBQXlSLFNBQVMsb0JBQW9CO0FBQ3RULE9BQU8sU0FBUztBQUNoQixPQUFPLFVBQVU7QUFDakIsT0FBTyxhQUFhO0FBQ3BCLFNBQVMsWUFBWTtBQUpyQixJQUFNLG1DQUFtQztBQU96QyxJQUFPLHNCQUFRLGFBQWE7QUFBQSxFQUN4QixTQUFTO0FBQUEsSUFDTCxJQUFJO0FBQUEsSUFDSixRQUFRO0FBQUEsTUFDSixTQUFTLEtBQUssUUFBUSxrQ0FBVyxrQkFBa0I7QUFBQSxJQUN2RCxDQUFDO0FBQUEsSUFDRCxLQUFLO0FBQUEsTUFDRCxTQUFTO0FBQUEsUUFDUCxFQUFFLEtBQUssYUFBYSxNQUFNLE9BQU87QUFBQSxNQUNuQztBQUFBLE1BQ0EsTUFBTTtBQUFBLElBQ1IsQ0FBQztBQUFBLEVBQ1A7QUFBQSxFQUNBLGNBQWM7QUFBQSxJQUNWLFNBQVMsQ0FBQyxTQUFTLFlBQVk7QUFBQSxFQUNuQztBQUFBLEVBQ0EsU0FBUztBQUFBLElBQ0wsT0FBTztBQUFBLE1BQ0g7QUFBQSxRQUNJLE1BQU07QUFBQSxRQUNOLGFBQWE7QUFBQSxNQUNqQjtBQUFBLE1BQ0E7QUFBQSxRQUNJLE1BQU07QUFBQSxRQUNOLGFBQWEsS0FBSyxRQUFRLGtDQUFXLFFBQVE7QUFBQSxNQUNqRDtBQUFBLElBQ0o7QUFBQSxFQUNKO0FBQ0osQ0FBQzsiLAogICJuYW1lcyI6IFtdCn0K
