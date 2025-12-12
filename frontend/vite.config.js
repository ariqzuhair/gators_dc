import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import { fileURLToPath, URL } from 'node:url'

// https://vitejs.dev/config/
export default defineConfig({
  plugins: [
    vue({
      template: {
        compilerOptions: {
          // Remove whitespace to reduce bundle size
          whitespace: 'condense'
        }
      }
    })
  ],
  
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  
  // Performance optimizations
  build: {
    // Enable minification with aggressive settings
    minify: 'terser',
    terserOptions: {
      compress: {
        drop_console: true, // Remove console.logs in production
        drop_debugger: true,
        pure_funcs: ['console.log', 'console.info', 'console.debug'], // Remove specific console methods
        passes: 2 // Multiple passes for better compression
      },
      mangle: {
        safari10: true // Ensure Safari 10 compatibility
      }
    },
    
    // Optimize chunk splitting for better caching
    rollupOptions: {
      output: {
        manualChunks: (id) => {
          // Split vendor chunks by package
          if (id.includes('node_modules')) {
            if (id.includes('vue') || id.includes('pinia')) {
              return 'vue-vendor'
            }
            if (id.includes('axios')) {
              return 'axios-vendor'
            }
            if (id.includes('@vueuse')) {
              return 'vueuse-vendor'
            }
            return 'vendor'
          }
          
          // Split UI components into separate chunk
          if (id.includes('/components/')) {
            return 'components'
          }
          
          // Split stores into separate chunk
          if (id.includes('/stores/')) {
            return 'stores'
          }
        },
        // Optimize chunk naming for caching
        chunkFileNames: 'assets/js/[name]-[hash].js',
        entryFileNames: 'assets/js/[name]-[hash].js',
        assetFileNames: 'assets/[ext]/[name]-[hash].[ext]'
      }
    },
    
    // Chunk size warnings
    chunkSizeWarningLimit: 800, // Stricter limit
    
    // Source maps (disable in production for better performance)
    sourcemap: false,
    
    // Target modern browsers for smaller bundle
    target: 'es2015',
    
    // Enable CSS code splitting
    cssCodeSplit: true,
    
    // Optimize CSS minification
    cssMinify: true,
    
    // Report compressed sizes
    reportCompressedSize: true,
    
    // Increase asset size limits for better inline optimization
    assetsInlineLimit: 4096 // 4kb (default)
  },
  
  server: {
    host: '0.0.0.0',
    port: 5173,
    watch: {
      usePolling: true
    },
    // Enable CORS for development
    cors: true
  },
  
  // Optimize dependencies
  optimizeDeps: {
    include: ['vue', 'vue-router', 'pinia', 'axios'],
    // Exclude large dependencies that don't need pre-bundling
    exclude: []
  },
  
  // Enable esbuild for faster builds
  esbuild: {
    // Remove console and debugger in production
    drop: process.env.NODE_ENV === 'production' ? ['console', 'debugger'] : [],
    // Enable minification
    legalComments: 'none'
  }
})
