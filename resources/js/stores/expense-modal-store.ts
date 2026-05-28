import {create} from 'zustand'

type ExpenseModalStore = {
    open: boolean;
    openModal: () => void;
    closeModal: () => void;
}

export const useExpenseModalStore = create<ExpenseModalStore>((set) => ({
    open: false,
    openModal: () => {
        set({
            open:true
        })
    },
    closeModal: () => {
        set({
            open:false
        })
    },
}))
