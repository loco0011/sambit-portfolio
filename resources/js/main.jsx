import '../css/app.css';
import { createRoot } from 'react-dom/client';
import App from './App';

createRoot(document.getElementById('root')).render(<App data={window.__PORTFOLIO__} />);
