import { useState } from 'react';
import type { InquiryFormData } from './features/inquiry/types/types.ts';
import { EditView } from './features/inquiry/components/EditView.tsx';
import { ConfirmView } from './features/inquiry/components/ConfirmView.tsx';
import { CompleteView } from './features/inquiry/components/CompleteView.tsx';
import {
  validateInquiry,
  getValidationErrors,
  getValidationErrorMessage,
} from './features/inquiry/validation/inquiryValidator';
import { saveSession } from './features/inquiry/adapters/formDataAdapter';
import { ThemeProvider } from './contexts/ThemeContext';
import { ThemeSelect } from './components/ui/ThemeSelect';

type FormStep = 'edit' | 'confirm' | 'complete';

export default function InquiryForm() {
  const [step, setStep] = useState<FormStep>('edit');
  const [formData, setFormData] = useState<InquiryFormData>({
    name: '',
    email: '',
    subject: '',
    message: '',
  });

  /**
   * フォームのデータを更新する
   * - inputのonChangeイベントで呼び出される
   * - 今までの入力内容をコピーし、変更された箇所のみを更新
   * @param e
   * @returns
   */
  const handleChange = (
    e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>,
  ) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value,
    });
  };

  /**
   * 確認画面に遷移する
   * - バリデーションを行い、エラーがある場合はエラーメッセージを表示
   * - エラーがない場合は確認画面に遷移
   * @param event
   * @returns
   */
  const handleToConfirm = (event: React.MouseEvent<HTMLButtonElement>) => {
    event.preventDefault();

    const isValid = validateInquiry(formData);
    if (!isValid) {
      const errors = getValidationErrors();
      alert(getValidationErrorMessage(errors));
      return;
    }

    setStep('confirm');
    setFormData(formData);
    saveSession(formData);
    // router.push('/confirm') など
  };

  return (
    <ThemeProvider>
      <ThemeSelect />
      <div className="container mx-auto max-w-3xl p-4 sm:p-8">
        <form id="inquiryForm" className="px-2">
          {step === 'edit' && (
            <EditView
              data={formData}
              onChange={handleChange}
              onNext={handleToConfirm}
            />
          )}
          {step === 'confirm' && (
            <ConfirmView
              data={formData}
              onBack={() => setStep('edit')}
              onComplete={() => setStep('complete')}
            />
          )}
          {step === 'complete' && <CompleteView data={formData} />}
        </form>
      </div>
    </ThemeProvider>
  );
}
