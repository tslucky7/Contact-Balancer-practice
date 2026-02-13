import './style.css';

const form = document.getElementById('inquiryForm') as HTMLFormElement;
const result = document.getElementById('result') as HTMLPreElement;

form.addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = new FormData(form);
  const payload = {
    name: String(formData.get('name') ?? ''),
    email: String(formData.get('email') ?? ''),
    subject: String(formData.get('subject') ?? ''),
    message: String(formData.get('message') ?? ''),
  };

  try {
    const response = await fetch('/api/inquiries.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload),
    });

    if (!response.ok) {
      throw new Error(`HTTP ${response.status}`);
    }

    const json = await response.json();
    result.textContent = JSON.stringify(json, null, 2);
    form.reset();
  } catch (error) {
    console.error("The connection failed.", error);
    result.textContent = `エラー: ${error instanceof Error ? error.message : 'Unknown error'}`;
  }
});
