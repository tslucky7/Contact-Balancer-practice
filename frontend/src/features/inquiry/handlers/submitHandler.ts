import { submitAPI } from '../api';
import { dom, state } from '../context';

/**
 * 問い合わせを送信する
 */
export const submitHandler = async (): Promise<void> => {
  const Payload = { ...state };

  try {
    const json = await submitAPI(Payload);
    // 変更予定：　okだったら画面上にレスポンスを描画する
    dom.result.textContent = JSON.stringify(json, null, 2);
    dom.form.reset();
  } catch (error) {
    console.error('The connection failed.', error);
    dom.result.textContent = `エラー: ${error instanceof Error ? error.message : 'Unknown error'}`;
  }
};
