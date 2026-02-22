import { routeHandler } from "./routeHandler";
import { STEPS } from "../types/types";

/**
 * 入力画面へ戻る
 */
export const toEditHandler = (event: Event): void => {
  event.preventDefault();
  history.replaceState(null, '', STEPS.EDIT);
  routeHandler();
};
