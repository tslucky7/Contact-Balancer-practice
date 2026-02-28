import type { state } from '../state/context';

/**
 * 問い合わせを送信する
 * @param Payload
 * @returns
 */
export const submitAPI = async (Payload: typeof state) => {
  try {
    const response = await fetch('/api/inquiries.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(Payload),
    });

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const json = await response.json();
    return json;
  } catch (error) {
    throw error;
  }
};
