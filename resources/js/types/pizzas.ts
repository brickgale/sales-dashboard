export interface PizzaType {
    id: string;
    slug?: string;
    name: string;
    category: string;
    ingredients: string;
    created_at?: string;
    updated_at?: string;
    pizzas?: Pizza[];
}

export interface Pizza {
    id: string;
    slug?: string;
    pizza_type_id: string;
    size: 'small' | 'medium' | 'large' | 'extra_large' | 'extra_extra_large';
    price: number;
    created_at?: string;
    updated_at?: string;
    pizza_type?: PizzaType;
}

export interface PizzaFormData {
    pizza_type_id: string;
    size: string;
    price: number;
}

export interface PizzaTypeFormData {
    name: string;
    category: string;
    ingredients: string;
}
