import { Outlet, Navigate } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function AuthLayout() {
    const { user, loading } = useAuth();

    if (loading) return (
        <div className="min-h-screen flex flex-col items-center justify-center bg-slate-950 text-white">
            <div className="w-12 h-12 border-4 border-indigo-500 border-t-transparent rounded-full animate-spin"></div>
            <p className="mt-4 font-medium tracking-wide">Loading Innoventure...</p>
        </div>
    );

    if (user) {
        return <Navigate to="/dashboard" replace />;
    }

    return (
        <div className="min-h-screen relative flex items-center justify-center bg-slate-950 font-sans selection:bg-indigo-500 selection:text-white py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
            {/* Ambient Background Blobs */}
            <div className="absolute top-0 left-0 w-[500px] h-[500px] bg-indigo-600/30 rounded-full mix-blend-screen filter blur-[100px] animate-pulse"></div>
            <div className="absolute bottom-0 right-0 w-[600px] h-[600px] bg-fuchsia-600/20 rounded-full mix-blend-screen filter blur-[120px] animate-pulse" style={{ animationDelay: '2s' }}></div>
            
            <div className="max-w-md w-full relative z-10 animate-fade-in-up">
                <div className="text-center mb-10">
                    <h1 className="text-4xl md:text-5xl font-black bg-gradient-to-r from-indigo-400 via-purple-400 to-fuchsia-400 bg-clip-text text-transparent drop-shadow-sm mb-2 tracking-tight">
                        INNOVENTURE
                    </h1>
                    <p className="text-slate-400 font-medium">Ignite Your Innovation</p>
                </div>
                
                <Outlet />
            </div>
        </div>
    );
}
