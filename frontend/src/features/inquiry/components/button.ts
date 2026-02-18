/**
 * ボタンを作成する
 * @param buttonType
 * @returns
 */
export function createButton(
  buttonType: 'button' | 'reset' | 'submit',
  text: string,
): HTMLButtonElement {
  const buttonElement = document.createElement('button');
  buttonElement.type = buttonType;
  buttonElement.id = buttonType === 'submit' ? 'inquiry-submit-button' : 'inquiry-back-button';
  buttonElement.className = 'w-max px-4 py-2 border text-white text-base';
  buttonElement.classList.add(
    buttonType === 'submit' ? 'bg-indigo-900' : 'bg-gray-500',
  );
  buttonElement.textContent = text;

  return buttonElement;
}