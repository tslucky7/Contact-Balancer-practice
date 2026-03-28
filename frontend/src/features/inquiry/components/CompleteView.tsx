import type { InquiryFormData } from '../types/types';
import { Heading } from '../../../components/ui/heading';

interface CompleteViewProps {
  data: InquiryFormData;
}

export const CompleteView = ({ data }: CompleteViewProps) => {
  return (
    <div id="step-complete" className="flex flex-col gap-y-4">
      <Heading className="text-center">お問い合わせ完了</Heading>
      <p className="text-center">お問い合わせありがとうございました。</p>
    </div>
  );
};
