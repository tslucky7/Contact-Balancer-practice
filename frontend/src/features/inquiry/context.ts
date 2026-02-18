export const dom = {
  form: document.getElementById('inquiryForm') as HTMLFormElement,
  toConfirmButton: document.getElementById('inquiry-to-confirm-button') as HTMLButtonElement,
  result: document.getElementById('result') as HTMLPreElement,
};

export const state = {
  name: '',
  email: '',
  subject: '',
  message: '',
};