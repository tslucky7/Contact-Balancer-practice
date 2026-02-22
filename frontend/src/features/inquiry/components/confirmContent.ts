/**
 * 確認画面の内容を作成する
 * @param content
 * @returns
 */
export function createConfirmContent(content: string): HTMLDivElement {
  const confirmContent = document.createElement('div');
  confirmContent.className = 'p-5 border rounded-lg bg-gray-50';
  const span = document.createElement('span');
  span.textContent = content;
  confirmContent.appendChild(span);

  return confirmContent;
}