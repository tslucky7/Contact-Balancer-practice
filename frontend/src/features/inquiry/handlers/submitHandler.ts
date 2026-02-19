import { submitAPI } from '../api';
import { dom, state } from '../context';
import { toCompleteHandler } from './toCompleteHandler';

/**
 * 問い合わせを送信する
 */
export const submitHandler = async (event: Event): Promise<void> => {
  // submit時のデフォルト送信の挙動を防ぐ(BEにリクエストは飛ぶが、リロードされて、javascriptが正常に実行されなくなる)
  event.preventDefault();
  
  const Payload = { ...state };

  try {
    const json = await submitAPI(Payload);
    // 変更予定：　okだったら画面上にレスポンスを描画する
    dom.result.textContent = JSON.stringify(json, null, 2);
    dom.form.reset();

    toCompleteHandler();
  } catch (error) {
    console.error('The connection failed.', error);
    dom.result.textContent = `エラー: ${error instanceof Error ? error.message : 'Unknown error'}`;
  }
};
