import { useState, useEffect } from 'react';
import { Link, useNavigate, useLocation } from 'react-router-dom';
import { useAuth } from '../context/AuthContext';

export default function Navbar() {
    const location = useLocation();
    const isLandingPage = location.pathname === '/';
    
    const [scrolled, setScrolled] = useState(!isLandingPage);
    const { user, logout } = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        if (!isLandingPage) {
            setScrolled(true);
            return;
        }
        
        const handleScroll = () => {
            setScrolled(window.scrollY > 50);
        };
        
        // Init check
        handleScroll();
        
        window.addEventListener('scroll', handleScroll);
        return () => window.removeEventListener('scroll', handleScroll);
    }, [isLandingPage]);

    const handleNavClick = (id) => {
        if (location.pathname !== '/') {
            navigate(`/#${id}`);
            // Small timeout to allow navigation to finish before scrolling
            setTimeout(() => {
                const element = document.getElementById(id);
                if (element) element.scrollIntoView({ behavior: 'smooth' });
            }, 100);
        } else {
            const element = document.getElementById(id);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }
    };

    return (
        <nav className={`fixed top-0 w-full z-50 transition-all duration-300 ${scrolled ? 'bg-brand-white/95 backdrop-blur-md shadow-sm py-4 border-b border-gray-200' : 'bg-transparent py-6'}`}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center h-10">
                    <div className="flex items-center">
                        <Link to="/" className="flex items-center text-xl md:text-2xl font-black text-brand-purple hover:text-brand-pink transition-colors tracking-tight">
                            <img src="/logo.png" alt="Innoventure Logo" className="h-8 md:h-10 w-auto mr-3" />
                            INNOVENTURE
                        </Link>
                    </div>

                    <div className="hidden md:flex items-center space-x-8">
                        <button onClick={() => handleNavClick('about')} className={`${scrolled ? 'text-brand-black' : 'text-brand-white'} font-semibold hover:text-brand-purple transition-colors`}>
                            About Us
                        </button>
                        <button onClick={() => handleNavClick('events')} className={`${scrolled ? 'text-brand-black' : 'text-brand-white'} font-semibold hover:text-brand-purple transition-colors`}>
                            Events
                        </button>
                        <button onClick={() => handleNavClick('timeline')} className={`${scrolled ? 'text-brand-black' : 'text-brand-white'} font-semibold hover:text-brand-purple transition-colors`}>
                            Timeline
                        </button>
                        <Link to="/leaderboard" className={`${scrolled ? 'text-brand-black' : 'text-brand-white'} font-semibold hover:text-brand-purple transition-colors`}>
                            Leaderboard
                        </Link>

                        {user ? (
                            <div className="flex items-center space-x-6">
                                <Link to="/dashboard" className="text-brand-purple font-bold hover:text-brand-pink transition-colors">
                                    Dashboard
                                </Link>
                                <div className="flex items-center space-x-4">
                                    <div className="flex flex-col items-end">
                                        <span className={`text-sm font-bold ${scrolled ? 'text-brand-black' : 'text-brand-white'}`}>{user.name}</span>
                                        <span className="text-xs font-semibold uppercase tracking-wider text-brand-purple">{user.role}</span>
                                    </div>
                                    <button onClick={logout} className="px-5 py-2 text-sm font-bold text-brand-black bg-gray-100 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2 transition-all duration-200">
                                        Logout
                                    </button>
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-center space-x-4">
                                <Link
                                    to="/seminar/register"
                                    className="px-6 py-2 text-sm font-bold text-brand-purple border-2 border-brand-purple rounded-full hover:bg-brand-purple hover:text-white transition-all duration-300"
                                >
                                    Daftar Seminar
                                </Link>
                                <Link
                                    to="/login"
                                    className="px-6 py-2.5 text-sm font-bold text-brand-white bg-brand-purple rounded-full hover:bg-brand-pink hover:text-brand-black transition-all duration-300 shadow-lg shadow-brand-purple/30 transform hover:-translate-y-0.5"
                                >
                                    Login
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </nav>
    );
}
