import { routeHandler } from "./routeHandler";

/**
 * 入力画面へ戻る
 */
export const toEditHandler = (event: Event): void => {
  event.preventDefault();
  history.replaceState(null, '', '/');
  routeHandler();
};
