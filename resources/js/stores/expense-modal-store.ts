import { Budget } from "@/types/budget";
import { Category } from "@/types/category";
import { Expense } from "@/types/expense";
import { create } from "zustand";

type ExpenseModalStore = {
    open: boolean;
    budget: Budget | null;
    expense: Expense | null;
    categories: Category[];
    openModal: () => void;
    editModal: (expense: Expense) => void;
    closeModal: () => void;
    setBudget: (budget: Budget) => void;
    setCategories: (categories: Category[]) => void;
};

export const useExpenseModalStore = create<ExpenseModalStore>((set) => ({
    open: false,
    budget: null,
    expense: null,
    categories: [],

    openModal: () => {
        set({
            open: true,
        });
    },

    editModal: (expense) => {
        set({
            open: true,
            expense
        });
    },

    closeModal: () => {
        set({
            open: false,
            expense: null,
        });
    },

    setBudget: (budget) => {
        set({
            budget,
        });
    },

    setCategories: (categories) => {
        set({
            categories,
        });
    },
}));


type Store = {
  open: boolean
  expense: Expense | null
  openModal: (expense: Expense) => void
  closeModal: () => void
}

export const useDeleteExpenseStore = create<Store>((set) => ({
  open: false,
  expense: null,
  openModal: (expense) =>
    set({
      open: true,
      expense,
    }),
  closeModal: () => {
    set({ open: false })
    setTimeout(() => {
      set({ expense: null })
    }, 200)
  }
}))