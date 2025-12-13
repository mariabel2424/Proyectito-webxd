import React from 'react';
import ReactDOM from 'react-dom/client';
import { BrowserRouter, Routes, Route } from 'react-router-dom';

// Importación de las rutas
import FinanzasDashboard from './Pages/Financiero/Dashboard';
import EntrenadorDashboard from './Pages/Entrenador/Dashboard';
import DeportistaDashboard from './Pages/Deportista/Dashboard';
import AdminDashboard from './Pages/Admin/Dashboard';
import TutorDashboard from './Pages/Tutor/Dashboard';
import RecepcionistaDashboard from './Pages/Recepcionista/Dashboard';
import InstructorDashboard from './Pages/Instructor/Dashboard';
import Login from './Pages/Login';
import Register from './Pages/Register';

ReactDOM.createRoot(document.getElementById("react-root")).render(
  <React.StrictMode>
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/login" element={<Login />} />
        <Route path="/register" element={<Register />} />
        <Route path="/financiero/dashboard" element={<FinanzasDashboard />} />
        <Route path="/entrenador/dashboard" element={<EntrenadorDashboard />} />
        <Route path="/deportista/dashboard" element={<DeportistaDashboard />} />
        <Route path="/admin/dashboard" element={<AdminDashboard />} />
        <Route path="/tutor/dashboard" element={<TutorDashboard />} />
        <Route path="/recepcionista/dashboard" element={<RecepcionistaDashboard />} />
        <Route path="/instructor/dashboard" element={<InstructorDashboard />} />
        {/* Añade otras rutas según sea necesario */}
      </Routes>
    </BrowserRouter>
  </React.StrictMode>
);
