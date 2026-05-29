import { Head, usePage } from "@inertiajs/react";
import { ToastContainer, toast } from "react-toastify";
import { Budget } from "@/types/budget";
import AmountDisplay from "@/Components/AmountDisplay";
import ExpenseModal from "@/Components/ExpenseModal";
import { useExpenseModalStore } from "@/stores/expense-modal-store";
import { Category } from "@/types/category";
import { useEffect } from "react";



type Props = {
    budget: Budget;
    categories: Category[];
}


export default function Show({ budget, categories }: Props) {

    const { flash } = usePage().props;

    // const { name, amount } = budget;
    // console.log(budget.id)
    // console.log(budget.name)
    // console.log(budget.type)

    const openModal = useExpenseModalStore(state => state.openModal)
    useExpenseModalStore.getState().setBudget(budget);
    useExpenseModalStore.getState().setCategories(categories);

    // console.log("categories: ", categories)

    useEffect(() => {
        if (flash.success) {
            toast.success(flash.success);
        }

        if (flash.error) {
            toast.error(flash.error);
        }
    }, [flash]);

    return (
        <>
            <Head title={`Presupuesto: ${budget.name}`} />
            <section className="sm:flex sm:items-center mt-10">
                <div className="sm:flex-auto">
                    <h1 className="font-bold text-4xl">Presupuesto: {budget.name}</h1>
                    <p className="mt-2 text-xl text-gray-500">Maneja tu Presupuesto, añade, quita o edita tus gastos aquí.</p>
                </div>
                <div className="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <a
                        href={'/dashboard'}
                        className="block bg-amber-500 text-white w-full px-5 py-3 rounded-lg  font-bold  text-xl cursor-pointer text-center">Volver a Presupuestos</a>
                </div>
            </section>

            <main className='grid grid-cols-1 md:grid-cols-2 items-center gap-20 mt-10'>

                <div className='space-y-5'>
                    <AmountDisplay label="Presupuestado" amount={+budget.amount} />
                    <AmountDisplay label="Gastado" amount={0}/>
                    <AmountDisplay label="Restante" amount={0}/>

                </div>
            </main>

            <section className="p-10 lg:px-5 shadow-lg mt-10">
                <div className="flex justify-between items-center">
                    <h2 className="text-3xl font-black">Gastos</h2>
                    <button className="bg-purple-950 hover:bg-purple-800 px-5 py-2 my-5 rounded-lg text-white font-bold text-xl cursor-pointer"
                    onClick={openModal}
                    >Nuevo Gasto</button>
                </div>
            </section>

            <ExpenseModal />

            <ToastContainer
                position="top-right"
                autoClose={5000}
                hideProgressBar={false}
                newestOnTop={false}
                closeOnClick
                rtl={false}
                pauseOnFocusLoss
                draggable
                pauseOnHover
                theme="dark"
            />
        </>
    )
}
