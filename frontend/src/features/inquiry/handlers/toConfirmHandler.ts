import { dom, state } from '../context';
import { createConfirmContent } from '../components/confirmContent';
import { createButton } from '../components/button';

/**
 * ボタンのクリックに応じて確認画面を構築
 * @param event
 * @returns
 */
export const toConfirmHandler = (event: Event): void => {
  const isValid = dom.form.checkValidity();
  if (!isValid) {
    event.preventDefault(); // 先に進ませない
    alert('すべての必須項目を入力してください');
    return;
  }

  event.preventDefault();

  // 入力画面を非表示化
  const editArea = document.getElementById('step-edit');
  editArea?.classList.add('hidden');

  // 入力された情報の取得
  const formData = new FormData(dom.form);
  state.name = formData.get('name') as string;
  state.email = formData.get('email') as string;
  state.subject = formData.get('subject') as string;
  state.message = formData.get('message') as string;

  // 確認画面の内容を作成
  const confirmName = createConfirmContent(state.name);
  const confirmEmail = createConfirmContent(state.email);
  const confirmSubject = createConfirmContent(state.subject);
  const confirmMessage = createConfirmContent(state.message);

  // ボタンを作成
  const submitButton = createButton('submit', '送信する');
  const backButton = createButton('button', '入力画面へ戻る');
  // 戻るボタンを押すと、入力画面へ戻る
  backButton.addEventListener('click', (e) => {
    e.preventDefault();

    // 確認画面を消して入力画面を表示
    document.getElementById('step-edit')?.classList.remove('hidden');

    // confirmArea をクリア（次に進む時に重複appendしないため）
    document.getElementById('step-confirm')?.replaceChildren();
  });

  // 確認用のタグに追加する
  const confirmArea = document.getElementById('step-confirm');
  confirmArea?.replaceChildren();
  confirmArea?.append(
    confirmName,
    confirmEmail,
    confirmSubject,
    confirmMessage,
    submitButton,
    backButton,
  );
};