/**
 * 完了画面の要素を動的に生成する
 * @returns HTMLDivElement
 */
export function createCompleteContent(): HTMLDivElement {
  const completeContent = document.createElement('div');
  completeContent.className = 'p-5 border rounded-lg bg-gray-50';
  const p = document.createElement('p');
  p.textContent = 'お問い合わせを受け付けました。\n内容を確認の上、担当者よりご連絡いたします。';
  completeContent.appendChild(p);
  console.log('完了画面生成');

  return completeContent;
}