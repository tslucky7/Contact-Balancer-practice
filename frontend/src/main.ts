import './style.css';

const form = document.getElementById('inquiryForm') as HTMLFormElement;
const button = form.querySelector('button') as HTMLButtonElement;
const result = document.getElementById('result') as HTMLPreElement;
/**
 * ボタンのクリックを処理する
 * @param event
 * @returns
 */
let formData: any = null;
button.addEventListener('click', (event) => {
  event.preventDefault();

  // 入力画面を非表示化
  const editArea = document.getElementById('step-edit');
  editArea?.classList.add('hidden');

  // 入力された情報を元に確認画面を生成
  const formData = new FormData(form);
  const name = formData.get('name') as string;
  const email = formData.get('email') as string;
  const subject = formData.get('subject') as string;
  const message = formData.get('message') as string;

  const confirmName = createConfirmContent(name);
  const confirmEmail = createConfirmContent(email);
  const confirmSubject = createConfirmContent(subject);
  const confirmMessage = createConfirmContent(message);

  // 確認用のタグに追加する
  const confirmArea = document.getElementById('step-confirm');
  confirmArea?.appendChild(confirmName);
  confirmArea?.appendChild(confirmEmail);
  confirmArea?.appendChild(confirmSubject);
  confirmArea?.appendChild(confirmMessage);
});

/**
 * フォームの送信を処理する
 * @param event
 * @returns
 */
form.addEventListener('submit', async (event) => {
  event.preventDefault();

  const Payload = {
    name: String(formData.get('name') ?? ''),
    email: String(formData.get('email') ?? ''),
    subject: String(formData.get('subject') ?? ''),
    message: String(formData.get('message') ?? ''),
  };

  try {
    const response = await fetch('/api/inquiries.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(Payload),
    });

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const json = await response.json();
    result.textContent = JSON.stringify(json, null, 2);
    form.reset();
  } catch (error) {
    console.error('The connection failed.', error);
    result.textContent = `エラー: ${error instanceof Error ? error.message : 'Unknown error'}`;
  }
});

/**
 * 確認画面の内容を作成する
 * @param content
 * @returns
 */
function createConfirmContent(content: string): HTMLDivElement {
  const confirmContent = document.createElement('div');
  const span = document.createElement('span');
  span.textContent = content;
  confirmContent.appendChild(span);

  return confirmContent;
}