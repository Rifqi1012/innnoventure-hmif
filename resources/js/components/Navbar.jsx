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

    const [isOpen, setIsOpen] = useState(false);

    return (
        <nav className={`fixed top-0 w-full z-50 transition-all duration-300 ${scrolled ? 'bg-brand-white/95 backdrop-blur-md shadow-sm py-3 md:py-4 border-b border-gray-200' : 'bg-transparent py-4 md:py-6'}`}>
            <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div className="flex justify-between items-center h-12 md:h-10">
                    <div className="flex items-center">
                        <Link to="/" className="flex items-center text-lg sm:text-xl md:text-2xl font-black text-brand-purple hover:text-brand-pink transition-colors tracking-tight truncate">
                            <img src="/logo.png" alt="Innoventure Logo" className="h-6 sm:h-8 md:h-10 w-auto mr-2 md:mr-3" />
                            INNOVENTURE
                        </Link>
                    </div>

                    {/* Desktop Menu */}
                    <div className="hidden lg:flex items-center space-x-6 xl:space-x-8">
                        <button onClick={() => handleNavClick('about')} className={`${scrolled ? 'text-brand-black' : 'text-brand-white'} font-semibold hover:text-brand-purple transition-colors whitespace-nowrap`}>
                            About Us
                        </button>
                        <button onClick={() => handleNavClick('events')} className={`${scrolled ? 'text-brand-black' : 'text-brand-white'} font-semibold hover:text-brand-purple transition-colors whitespace-nowrap`}>
                            Events
                        </button>
                        <button onClick={() => handleNavClick('timeline')} className={`${scrolled ? 'text-brand-black' : 'text-brand-white'} font-semibold hover:text-brand-purple transition-colors whitespace-nowrap`}>
                            Timeline
                        </button>
                        <Link to="/leaderboard" className={`${scrolled ? 'text-brand-black' : 'text-brand-white'} font-semibold hover:text-brand-purple transition-colors whitespace-nowrap`}>
                            Leaderboard
                        </Link>

                        {user ? (
                            <div className="flex items-center space-x-4 xl:space-x-6">
                                <Link to="/dashboard" className="text-brand-purple font-bold hover:text-brand-pink transition-colors whitespace-nowrap">
                                    Dashboard
                                </Link>
                                <div className="flex items-center space-x-4 border-l border-gray-300/30 pl-4 xl:pl-6">
                                    <div className="flex flex-col items-end">
                                        <span className={`text-sm font-bold ${scrolled ? 'text-brand-black' : 'text-brand-white'} truncate max-w-[120px] xl:max-w-[150px]`}>{user.name}</span>
                                        <span className="text-[10px] xl:text-xs font-semibold uppercase tracking-wider text-brand-purple">{user.role}</span>
                                    </div>
                                    <button onClick={logout} className="px-4 xl:px-5 py-2 text-sm font-bold text-brand-black bg-gray-100 rounded-full hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-brand-purple focus:ring-offset-2 transition-all duration-200">
                                        Logout
                                    </button>
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-center space-x-4">
                                <Link
                                    to="/seminar/register"
                                    className={`px-4 xl:px-6 py-2 text-sm font-bold border-2 rounded-full transition-all duration-300 whitespace-nowrap ${scrolled ? 'border-brand-purple text-brand-purple hover:bg-brand-purple hover:text-white' : 'border-white text-white hover:bg-white hover:text-brand-purple'}`}
                                >
                                    Daftar Seminar
                                </Link>
                                <Link
                                    to="/login"
                                    className="px-5 xl:px-6 py-2.5 text-sm font-bold text-brand-white bg-brand-purple rounded-full hover:bg-brand-pink hover:text-brand-black transition-all duration-300 shadow-lg shadow-brand-purple/30 transform hover:-translate-y-0.5 whitespace-nowrap"
                                >
                                    Login
                                </Link>
                            </div>
                        )}
                    </div>

                    {/* Mobile Menu Button */}
                    <div className="lg:hidden flex items-center">
                        <button 
                            onClick={() => setIsOpen(!isOpen)} 
                            className={`p-2 rounded-md focus:outline-none ${scrolled ? 'text-brand-black hover:bg-gray-100' : 'text-brand-white hover:bg-white/10'}`}
                        >
                            {isOpen ? (
                                <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M6 18L18 6M6 6l12 12" /></svg>
                            ) : (
                                <svg className="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4 6h16M4 12h16M4 18h16" /></svg>
                            )}
                        </button>
                    </div>
                </div>
            </div>

            {/* Mobile Menu Dropdown */}
            {isOpen && (
                <div className={`lg:hidden w-full border-t ${scrolled ? 'bg-brand-white border-gray-200 shadow-lg' : 'bg-brand-black/95 backdrop-blur-md border-gray-800'}`}>
                    <div className="px-4 pt-2 pb-6 space-y-2">
                        <button onClick={() => { handleNavClick('about'); setIsOpen(false); }} className={`block w-full text-left px-3 py-3 rounded-md font-semibold ${scrolled ? 'text-brand-black hover:bg-gray-100' : 'text-brand-white hover:bg-white/10'}`}>
                            About Us
                        </button>
                        <button onClick={() => { handleNavClick('events'); setIsOpen(false); }} className={`block w-full text-left px-3 py-3 rounded-md font-semibold ${scrolled ? 'text-brand-black hover:bg-gray-100' : 'text-brand-white hover:bg-white/10'}`}>
                            Events
                        </button>
                        <button onClick={() => { handleNavClick('timeline'); setIsOpen(false); }} className={`block w-full text-left px-3 py-3 rounded-md font-semibold ${scrolled ? 'text-brand-black hover:bg-gray-100' : 'text-brand-white hover:bg-white/10'}`}>
                            Timeline
                        </button>
                        <Link onClick={() => setIsOpen(false)} to="/leaderboard" className={`block w-full text-left px-3 py-3 rounded-md font-semibold ${scrolled ? 'text-brand-black hover:bg-gray-100' : 'text-brand-white hover:bg-white/10'}`}>
                            Leaderboard
                        </Link>

                        {user ? (
                            <div className={`pt-4 mt-2 border-t ${scrolled ? 'border-gray-200' : 'border-gray-800'}`}>
                                <Link onClick={() => setIsOpen(false)} to="/dashboard" className={`block w-full text-left px-3 py-3 rounded-md font-bold ${scrolled ? 'text-brand-purple hover:bg-brand-purple/10' : 'text-brand-pink hover:bg-brand-pink/10'}`}>
                                    Dashboard
                                </Link>
                                <div className="px-3 py-4 flex items-center justify-between">
                                    <div className="flex flex-col">
                                        <span className={`text-sm font-bold ${scrolled ? 'text-brand-black' : 'text-brand-white'}`}>{user.name}</span>
                                        <span className="text-xs font-semibold uppercase tracking-wider text-brand-purple">{user.role}</span>
                                    </div>
                                    <button onClick={() => { logout(); setIsOpen(false); }} className={`px-4 py-2 text-sm font-bold rounded-full ${scrolled ? 'bg-gray-200 text-brand-black hover:bg-gray-300' : 'bg-white/10 text-brand-white hover:bg-white/20'}`}>
                                        Logout
                                    </button>
                                </div>
                            </div>
                        ) : (
                            <div className={`pt-4 mt-2 border-t ${scrolled ? 'border-gray-200' : 'border-gray-800'} flex flex-col space-y-3 px-3`}>
                                <Link
                                    onClick={() => setIsOpen(false)}
                                    to="/seminar/register"
                                    className={`w-full text-center px-6 py-3 text-sm font-bold border-2 rounded-full transition-all duration-300 ${scrolled ? 'border-brand-purple text-brand-purple hover:bg-brand-purple hover:text-white' : 'border-brand-pink text-brand-pink hover:bg-brand-pink hover:text-brand-black'}`}
                                >
                                    Daftar Seminar
                                </Link>
                                <Link
                                    onClick={() => setIsOpen(false)}
                                    to="/login"
                                    className={`w-full text-center px-6 py-3 text-sm font-bold rounded-full transition-all duration-300 ${scrolled ? 'bg-brand-purple text-white hover:bg-brand-pink hover:text-brand-black' : 'bg-brand-pink text-brand-black hover:bg-white'}`}
                                >
                                    Login
                                </Link>
                            </div>
                        )}
                    </div>
                </div>
            )}
        </nav>
    );
}
