import type React from "react";

interface ButtonProps {
  buttonType: 'button' | 'reset' | 'submit';
  text: string;
  eventHandler: (event: React.MouseEvent<HTMLButtonElement>) => void;
}

/**
 * ボタンの要素・イベントハンドラーを一度に設定して生成する
 * @param buttonType
 * @param text
 * @param eventHandler
 * @returns
 */
export const Button = ({
  buttonType,
  text,
  eventHandler,
}: ButtonProps) => {
  const baseStyles =
    'w-max border px-4 py-2 text-right text-base text-white transition-colors';

  const variantStyles =
    buttonType === 'submit'
      ? 'border-indigo-950 bg-indigo-900 hover:bg-indigo-800 dark:border-indigo-400 dark:bg-indigo-500 dark:hover:bg-indigo-400'
      : 'border-slate-600 bg-slate-600 hover:bg-slate-500 dark:border-slate-400 dark:bg-slate-500 dark:hover:bg-slate-400';

  return (
    <button
      id={
        buttonType === 'submit'
          ? 'inquiry-submit-button'
          : 'inquiry-back-button'
      }
      className={`${baseStyles} ${variantStyles}`}
      type={buttonType}
      onClick={eventHandler}
    >
      {text}
    </button>
  );
};
