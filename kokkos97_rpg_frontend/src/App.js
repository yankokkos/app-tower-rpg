// src/App.js
import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom'; // Atualize aqui
import HomePage from './pages/HomePage';
import FichaDetailsPage from './pages/FichaDetailsPage';
import FichaForm from './pages/FichaForm';
import Dashboard from './pages/Dashboard';
import DashboardMaster from './pages/DashboardMaster';
import DashboardVisitante from './pages/DashboardVisitante';
import Tracker from './pages/Tracker';
import LoginPage from './pages/LoginPage';
import RegisterPage from './pages/RegisterPage';

function App() {
    return (
        <Router>
            <div className="App">
                <Routes> {/* Atualize aqui */}
                    <Route path="/" element={<HomePage />} />
                    <Route path="/login" element={<LoginPage />} />
                    <Route path="/register" element={<RegisterPage />} />
                    <Route path="/ficha/:id" element={<FichaDetailsPage />} />
                    <Route path="/ficha-form" element={<FichaForm />} />
                    <Route path="/dashboard" element={<Dashboard />} />
                    <Route path="/dashboard-master" element={<DashboardMaster />} />
                    <Route path="/dashboard-visitante" element={<DashboardVisitante />} />
                    <Route path="/tracker" element={<Tracker />} />
                    {/* Adicione outras rotas conforme necessário */}
                </Routes> {/* Atualize aqui */}
            </div>
        </Router>
    );
}

export default App;