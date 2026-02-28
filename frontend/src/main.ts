import './style.css';

import { toConfirmHandler } from './features/inquiry/handlers/toConfirmHandler';
import { dom } from './features/inquiry/state/context';
import { routeHandler } from './features/inquiry/handlers/routeHandler';

// ルートを処理する
window.addEventListener('load', routeHandler);
dom.toConfirmButton.addEventListener('click', toConfirmHandler);
