import { Outlet } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';
import Navbar from '../components/Navbar';

export default function MainLayout() {
    const { loading } = useAuth();

    if (loading) return (
        <div className="min-h-screen flex flex-col items-center justify-center bg-brand-black text-brand-white">
            <div className="w-12 h-12 border-4 border-brand-purple border-t-transparent rounded-full animate-spin"></div>
            <p className="mt-4 font-medium tracking-wide">Loading Innoventure...</p>
        </div>
    );

    return (
        <div className="min-h-screen bg-brand-white font-sans selection:bg-brand-pink selection:text-brand-black pb-12 pt-24">
            <Navbar />

            <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 animate-fade-in-up">
                <Outlet />
            </main>
        </div>
    );
}
