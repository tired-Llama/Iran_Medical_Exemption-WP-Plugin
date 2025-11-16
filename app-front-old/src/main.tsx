import React from 'react'
import ReactDOM from 'react-dom/client'
import App from './App'
import './index.css'

declare global {
  interface Window {
    medicalExemptionData: {
      ajaxUrl: string
      nonce: string
      apiUrl: string
      pluginUrl: string
    }
  }
}

// Wait for DOM to be ready
const initApp = () => {
  const root = document.getElementById('medical-exemption-root')
  if (root) {
    ReactDOM.createRoot(root).render(
      <React.StrictMode>
        <App />
      </React.StrictMode>
    )
  }
}

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initApp)
} else {
  initApp()
}