import './style.css';

import React from 'react';
import ReactDOM from 'react-dom/client';
import InquiryForm from './App';

ReactDOM.createRoot(document.getElementById('root') as HTMLElement).render(
  // StrictMode: 開発時のみレンダリングやuseEffectが2回実行される
  <React.StrictMode>
    <InquiryForm />
  </React.StrictMode>
);

