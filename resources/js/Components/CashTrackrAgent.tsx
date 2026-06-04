import { useRef, useState } from 'react';
import { useChat } from '@ai-sdk/react'
import { DefaultChatTransport } from 'ai';
import { toast } from 'react-toastify';
import { router } from '@inertiajs/react';

type Props = {
    budgetId: number,
    name: string,

}


export default function CashTrackrAgent({ budgetId, name }: Props) {

    const [input, setInput] = useState('');
    const [isScanning, setIsScanning] = useState(false);
    const fileInputRef = useRef<HTMLInputElement>(null);

    const { sendMessage, messages, setMessages, status } = useChat({
        transport: new DefaultChatTransport({
            api: `/dashboard/budgets/${budgetId}/chat`
        }),
        onFinish: ({ message }) => {
            const expenseCreated = message.parts.some(part => {
                // if (!part.output) return null;
                // return part.output.startsWith('[EXPENSE_CREATED]')

                const isAddExpenseTool = part.type === 'tool-AddExpense';
                const finished = 'state' in part && part.state === 'output-available';


                return isAddExpenseTool && finished;
            });

            if (expenseCreated) {
                toast.success('Gasto registrado correctamente');
                router.reload();
            }


        }
    });

    // console.log("Messages: ", messages);

    const handleImageUpload = async (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0];
        if (!file) return;

        setIsScanning(true);

        setMessages(prev => [
            ...prev,
            {
                id: crypto.randomUUID(),
                role: 'user' as const,
                content: 'Ticket de Compra subido',
                parts: [
                    { type: 'text', text: 'Ticket de Compra subido' }
                ]
            }
        ])

        try {

            const csrfToken = document.querySelector<HTMLMetaElement>('meta[name="csrf-token"]')?.getAttribute('content');
            const formData = new FormData();
            formData.append('image', file);
            formData.append('message', `Analiza este ticket`);

            const response = await fetch(`/dashboard/budgets/${budgetId}/scan-ticket`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken || '',
                    'Accept': 'application/json'
                },
                body: formData
            });


            const data = await response.json();

            setMessages(prev => [
                ...prev,
                {
                    id: crypto.randomUUID(),
                    role: 'assistant' as const,
                    content: data.message,
                    parts: [
                        { type: 'text', text: data.message }
                    ]
                }
            ]);

            if (data.success) {
                toast.success(data.message);
                router.reload();
            }

        } catch (error) {
            console.log("Error al procesar el Ticket: ", error)

            setMessages(prev => [
                ...prev,
                {
                    id: crypto.randomUUID(),
                    role: 'assistant' as const,
                    content: 'Error al procesar el Ticket. Intenta de nuevo.',
                    parts: [
                        { type: 'text', text: 'Error al procesar el Ticket. Intenta de nuevo.' }
                    ]
                }
            ])
        } finally {
            setIsScanning(false);
            if (fileInputRef.current) fileInputRef.current.value = '';
        }

    }

    const isBusy = status === "streaming" || status === 'submitted';

    return (
        <section className='p-10 lg:px-5 shadow-lg mt-10'>
            <h2 className="text-3xl font-bold">Pregunta sobre tu Presupuesto, añade gastos y más.</h2>
            <div className="space-y-3 mb-4 mt-8">
                {messages.map(m => (
                    <div
                        key={m.id}
                        className={`p-3 rounded-lg max-w-[80%] lg:max-w-[60%] ${m.role === 'user'
                            ? 'bg-amber-500 text-white ml-auto'
                            : 'bg-gray-700 text-white mr-auto'
                            }`}

                    >
                        {m.parts.map((part, i) => {
                            if (part.type !== 'text') return null;

                            const text = part.text
                                .replace('[EXPENSE_CREATED]', '')
                                .trim();

                            if (!text) return null;

                            return (
                                <p className='text-xl' key={i}>
                                    <strong>{m.role === 'user' ? name : 'CashTrackr IA: '}</strong>
                                    {text}
                                </p>
                            )

                        })}
                    </div>
                ))}


                {isScanning && (
                    <div className="bg-gray-100 mr-auto max-w-[80%] lg:max-w-[60%] p-3 rounded-lg">
                        <p className="text-xl">
                            <strong>CashTrackr IA:</strong>Analizando ticket...
                        </p>
                    </div>
                )

                }
            </div>

            <form
                onSubmit={(e) => {
                    e.preventDefault();
                    // Handle form submission
                    if (input.trim()) {
                        sendMessage({ text: input });
                        setInput('')
                    }

                    setInput('');
                }}
                className="flex flex-col gap-2"
            >
                <textarea
                    value={input}
                    onChange={(e) => setInput(e.target.value)}
                    placeholder="Consulta dudas sobre tu Presupuesto o Agrega Gastos"
                    className="w-full border border-gray-300 p-3 rounded-lg text-xl"
                    disabled={isBusy}
                />
                <div className="flex gap-2">
                    <button
                        type="submit"
                        className="flex-1 mt-5 bg-purple-950 hover:bg-purple-800 p-3 rounded-lg text-white font-bold text-xl cursor-pointer disabled:opacity-20"
                        disabled={isBusy || input.trim().length === 0}
                    >
                        {status === 'streaming' ? 'Procesando...' : 'Consultar'}
                    </button>
                    <button
                        type="button"
                        onClick={() => fileInputRef.current?.click()}
                        className="mt-5 bg-amber-500 hover:bg-amber-500 p-3 rounded-lg text-white font-bold text-xl cursor-pointer disabled:opacity-20"
                        disabled={isBusy}
                    >
                        {isScanning ? 'Escaneando...' : 'Subir Ticket'}
                    </button>
                </div>
                <input
                    type="file"
                    accept="image/*"
                    className="hidden"
                    ref={fileInputRef}
                    onChange={handleImageUpload}
                />
            </form>
        </section>
    );
}
