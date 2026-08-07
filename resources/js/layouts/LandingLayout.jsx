import { Outlet, Link } from 'react-router-dom';
import Navbar from '../components/Navbar';

export default function LandingLayout() {
    const scrollToSection = (id) => {
        const element = document.getElementById(id);
        if (element) {
            element.scrollIntoView({ behavior: 'smooth' });
        }
    };

    return (
        <div className="min-h-screen bg-brand-white font-sans selection:bg-brand-pink selection:text-brand-black flex flex-col">
            <Navbar />

            {/* Main Content Area */}
            <main className="flex-grow w-full">
                <Outlet />
            </main>

            {/* Complex Footer */}
            <footer className="bg-brand-black text-brand-white border-t-4 border-brand-purple pt-20 pb-10">
                <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-12 mb-16">
                        <div className="md:col-span-2">
                            <h2 className="text-4xl font-black text-brand-pink mb-6 tracking-tight">INNOVENTURE CHAPTER II</h2>
                            <p className="text-gray-400 text-lg max-w-md leading-relaxed">
                                The biggest National Technology Seminar and Competition designed to foster innovation and build the next generation of tech leaders.
                            </p>
                        </div>

                        <div>
                            <h3 className="text-xl font-bold text-brand-white mb-6">Explore</h3>
                            <ul className="space-y-4 text-gray-400">
                                <li><button onClick={() => scrollToSection('about')} className="hover:text-brand-pink transition-colors">About Us</button></li>
                                <li><button onClick={() => scrollToSection('events')} className="hover:text-brand-pink transition-colors">Competitions</button></li>
                                <li><button onClick={() => scrollToSection('events')} className="hover:text-brand-pink transition-colors">Seminar</button></li>
                                <li><Link to="/login" className="hover:text-brand-pink transition-colors">Login</Link></li>
                            </ul>
                        </div>

                        <div>
                            <h3 className="text-xl font-bold text-brand-white mb-6">Contact Us</h3>
                            <ul className="space-y-4 text-gray-400">
                                <li>Jl. Dipati ukur No.112-116</li>
                                <li></li>
                                <li>+62 812 3456 7890</li>
                            </ul>
                        </div>
                    </div>

                    <div className="border-t border-gray-800 pt-8 flex flex-col md:flex-row justify-between items-center text-gray-500 text-sm font-medium">
                        <p>&copy; {new Date().getFullYear()} HIMF Innoventure CHAPTER II. All rights reserved.</p>
                        <div className="mt-4 md:mt-0 space-x-6">
                            <a href="#" className="hover:text-brand-pink transition-colors">Instagram</a>
                            <a href="#" className="hover:text-brand-pink transition-colors">LinkedIn</a>
                            <a href="#" className="hover:text-brand-pink transition-colors">Twitter</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
