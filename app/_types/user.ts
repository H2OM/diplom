export interface User {
    id: number;
    first_name: string;
    second_name: string;
    phone: string;
    age: number;
    gender: 'female' | 'male';
    email: string;
}

export interface UserSignInData {
    phone: string;
    password: string;
}

export interface UserSignUpData extends Omit<User, 'id'> {
    password: string;
    password_confirmed: string;
}