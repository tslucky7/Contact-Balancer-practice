import type { InquiryDom, InquiryState } from "../types/types";

export const dom: InquiryDom = {
  form: document.getElementById('inquiryForm') as HTMLFormElement,
  heading: document.getElementById('inquiry-heading') as HTMLHeadingElement,
  stepEdit: document.getElementById('step-edit') as HTMLDivElement,
  stepConfirm: document.getElementById('step-confirm') as HTMLDivElement,
  stepComplete: document.getElementById('step-complete') as HTMLDivElement,
  toConfirmButton: document.getElementById('inquiry-to-confirm-button') as HTMLButtonElement,
};

export const state: InquiryState = {
  name: '',
  email: '',
  subject: '',
  message: '',
};
