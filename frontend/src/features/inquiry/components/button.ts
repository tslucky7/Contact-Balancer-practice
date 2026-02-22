/**
 * ボタンの要素・イベントハンドラーを一度に設定して生成する
 * @param buttonType
 * @param text
 * @param eventHandler
 * @returns
 */
export function createButton(
  buttonType: 'button' | 'reset' | 'submit',
  text: string,
  eventHandler: (event: Event) => void,
): HTMLButtonElement {
  const buttonElement = document.createElement('button');
  buttonElement.type = buttonType;
  buttonElement.id = buttonType === 'submit' ? 'inquiry-submit-button' : 'inquiry-back-button';
  buttonElement.className = 'w-max px-4 py-2 border text-white text-base';
  buttonElement.classList.add(
    buttonType === 'submit' ? 'bg-indigo-900' : 'bg-gray-500',
  );
  buttonElement.textContent = text;
  // ボタンをクリックした時の処理を設定
  buttonElement.addEventListener('click', eventHandler);

  return buttonElement;
}