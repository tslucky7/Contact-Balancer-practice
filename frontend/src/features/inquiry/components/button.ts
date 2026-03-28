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
  buttonElement.className =
    'w-max border px-4 py-2 text-base text-white transition-colors';
  buttonElement.classList.add(
    ...(buttonType === 'submit'
      ? [
          'border-indigo-950',
          'bg-indigo-900',
          'hover:bg-indigo-800',
          'dark:border-indigo-400',
          'dark:bg-indigo-500',
          'dark:hover:bg-indigo-400',
        ]
      : [
          'border-slate-600',
          'bg-slate-600',
          'hover:bg-slate-500',
          'dark:border-slate-400',
          'dark:bg-slate-500',
          'dark:hover:bg-slate-400',
        ]),
  );
  buttonElement.textContent = text;
  // ボタンをクリックした時の処理を設定
  buttonElement.addEventListener('click', eventHandler);

  return buttonElement;
}
