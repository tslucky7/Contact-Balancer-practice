import { submitAPI } from '../api';
import { dom, state } from '../context';
import { toCompleteHandler } from './toCompleteHandler';

/**
 * 問い合わせを送信する
 */
export const submitHandler = async (event: Event): Promise<void> => {
  // 念の為、submitで実行されたことを確認
  const target = event.target as HTMLButtonElement;
  if (!(target.type === 'submit')) {
    console.error('submitHandler: event is not a SubmitEvent');
    return;
  }

  // submit時のデフォルト送信の挙動を防ぐ(デフォルトの送信だとBEにリクエストは飛ぶが、リロードされること・javascriptが正常に実行されない事象が発生する)
  event.preventDefault();
  
  const Payload = { ...state };

  try {
    const json = await submitAPI(Payload);
    // 変更予定：　okだったら画面上にレスポンスを描画する
    console.log('Response JSON:', json);
    dom.form.reset();

    toCompleteHandler();
  } catch (error) {
    console.error('The connection failed.', error);
  }
};
