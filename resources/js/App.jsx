import { BrowserRouter as Router, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './context/AuthContext';
import LandingLayout from './layouts/LandingLayout';
import Landing from './pages/landing/Landing';
import AuthLayout from './layouts/AuthLayout';
import MainLayout from './layouts/MainLayout';
import ProtectedRoute from './layouts/ProtectedRoute';
import Login from './pages/auth/Login';
import Dashboard from './pages/dashboard/Dashboard';
import WebDev from './pages/competition/WebDev';
import UiUx from './pages/competition/UiUx';
import MobileLegends from './pages/competition/MobileLegends';
import SeminarRegister from './pages/seminar/SeminarRegister';
import AssessmentDashboard from './pages/dashboard/AssessmentDashboard';
import './index.css';

function App() {
    return (
        <AuthProvider>
            <Router>
                <Routes>
                    <Route element={<LandingLayout />}>
                        <Route path="/" element={<Landing />} />
                        <Route path="/leaderboard" element={<AssessmentDashboard />} />
                    </Route>
                    
                    <Route element={<AuthLayout />}>
                        <Route path="/login" element={<Login />} />
                    </Route>
                    
                    <Route element={<MainLayout />}>
                        {/* Public but wrapped in MainLayout */}
                        <Route path="/seminar/register" element={<SeminarRegister />} />
                        
                        {/* Protected Routes */}
                        <Route element={<ProtectedRoute />}>
                            <Route path="/dashboard" element={<Dashboard />} />
                            <Route path="/competition/webdev" element={<WebDev />} />
                            <Route path="/competition/uiux" element={<UiUx />} />
                            <Route path="/competition/ml" element={<MobileLegends />} />
                        </Route>
                    </Route>
                </Routes>
            </Router>
        </AuthProvider>
    );
}

export default App;
