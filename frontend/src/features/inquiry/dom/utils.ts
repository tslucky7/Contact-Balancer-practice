import { setHeading } from "../components/heading";

/**
 * 各ステップの表示状態を初期化し、編集画面を表示可能な状態にする
 */
export const resetStepsToEdit = (dom: { 
  stepEdit: HTMLElement, 
  stepConfirm: HTMLElement, 
  stepComplete: HTMLElement 
}): void => {
  setHeading('お問い合わせ');
  dom.stepEdit.classList.remove('hidden');
  dom.stepConfirm.replaceChildren();
  dom.stepComplete.replaceChildren();
};
