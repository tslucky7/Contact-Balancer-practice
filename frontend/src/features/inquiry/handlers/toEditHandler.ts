import { dom } from "../state/context";
import { STEPS } from "../types/types";

/**
 * 入力画面へ戻る
 */
export const toEditHandler = (event: Event): void => {
  event.preventDefault();
  history.replaceState(null, '', STEPS.EDIT);

  dom.stepEdit.classList.remove('hidden');
  dom.stepConfirm.replaceChildren();
  dom.stepComplete.replaceChildren();
};
