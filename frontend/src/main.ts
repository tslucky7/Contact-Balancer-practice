import './style.css';

import { toConfirmHandler } from './features/inquiry/handlers/toConfirmHandler';
import { submitHandler } from './features/inquiry/handlers/submitHandler';
import { dom } from './features/inquiry/context';

dom.toConfirmButton.addEventListener('click', toConfirmHandler);
dom.form.addEventListener('submit', submitHandler);
