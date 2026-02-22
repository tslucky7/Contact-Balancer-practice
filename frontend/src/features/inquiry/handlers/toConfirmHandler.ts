import { dom, state, STEPS } from '../context';
import { createConfirmContent } from '../components/confirmContent';
import { createButton } from '../components/button';
import { toEditHandler } from './toEditHandler';
import { submitHandler } from './submitHandler';
import { setHeading } from '../components/heading';

/**
 * ボタンのクリックに応じて確認画面を構築
 * @param event
 * @returns
 */
export const toConfirmHandler = (event: Event): void => {
  // 必須項目が全て入力されているかをチェック
  const isValid = dom.form.checkValidity();
  if (!isValid) {
    event.preventDefault(); // 先に進ませない
    alert('すべての必須項目を入力してください');
    return;
  }

  event.preventDefault();

  history.pushState({ step: STEPS.CONFIRM }, '', '/confirm');

  // 入力画面を非表示化
  dom.stepEdit.classList.add('hidden');

  // 入力された情報の取得
  const formData = new FormData(dom.form);
  state.name = formData.get('name') as string;
  state.email = formData.get('email') as string;
  state.subject = formData.get('subject') as string;
  state.message = formData.get('message') as string;

  // 確認画面の要素を作成
  const confirmName = createConfirmContent(state.name);
  const confirmEmail = createConfirmContent(state.email);
  const confirmSubject = createConfirmContent(state.subject);
  const confirmMessage = createConfirmContent(state.message);
  //　submitはボタンの要素のみ生成。キーボード時の操作に対応するため、イベントハンドラーはmain.tsで設定。
  const submitButton = createButton('submit', '送信する', submitHandler);
  const backButton = createButton('button', '入力画面へ戻る', toEditHandler);
  
  // 確認用のタグに生成した要素を追加する
  dom.stepConfirm.replaceChildren();
  setHeading('確認画面');
  dom.stepConfirm.append(
    confirmName,
    confirmEmail,
    confirmSubject,
    confirmMessage,
    submitButton,
    backButton,
  );
};
