import { dom } from "../state/context";
import { STEPS } from "../types/types";
import { resetStepsToEdit } from "../dom/utils";

/**
 * 入力画面へ戻る
 */
export const toEditHandler = (event: Event): void => {
  event.preventDefault();
  history.replaceState(null, '', STEPS.EDIT);

  resetStepsToEdit(dom);
};
