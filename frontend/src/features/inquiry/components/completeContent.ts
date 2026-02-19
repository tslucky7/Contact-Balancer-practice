export function createCompleteContent(): HTMLDivElement {
  const completeContent = document.createElement('div');
  const span = document.createElement('span');
  span.textContent = '送信完了';
  completeContent.appendChild(span);
  console.log('完了画面生成');

  return completeContent;
}