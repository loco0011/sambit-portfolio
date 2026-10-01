import '../../css/admin.css';
import { createRoot } from 'react-dom/client';
import App from './App';

createRoot(document.getElementById('admin')).render(<App boot={window.__ADMIN__} />);
