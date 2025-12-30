import React, { useState, useEffect } from 'react';
import axios from 'axios';
import Sidebar from './Sidebar';
import Topbar from './Topbar';
import '../../../css/admin/dashboard.css';
import { 
  Home, 
  Users, 
  TrendingUp, 
  Activity, 
  User as UserIcon, 
  Settings, 
  HelpCircle,
  LogOut,
  Mail,
  Phone,
  MapPin,
  Shield,
  CheckCircle,
  XCircle
} from 'lucide-react';

export default function Dashboard() {
    const [user, setUser] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState('');
    const [stats, setStats] = useState({
        usuarios: 0,
        deportistas: 0,
        ingresos: 0,
        actividades: 0
    });

    useEffect(() => {
        cargarUsuario();
        cargarEstadisticas();
    }, []);

    const cargarUsuario = async () => {
        try {
            const token = localStorage.getItem('token');
            if (!token) {
                window.location.href = '/login';
                return;
            }

            const userLocal = localStorage.getItem('user');
            if (userLocal) {
                setUser(JSON.parse(userLocal));
                setLoading(false);
                return;
            }

            const response = await axios.get(
                'http://localhost:8000/api/auth/me',
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json',
                    },
                }
            );

            setUser(response.data);
            localStorage.setItem('user', JSON.stringify(response.data));
            setLoading(false);
        } catch (err) {
            if (err.response?.status === 401) {
                localStorage.removeItem('token');
                localStorage.removeItem('user');
                window.location.href = '/login';
            } else {
                setError('Error al cargar los datos del usuario');
                setLoading(false);
            }
        }
    };

    const cargarEstadisticas = async () => {
        try {
            // Aquí puedes hacer llamadas a tu API para obtener estadísticas reales
            // Por ahora usamos datos de ejemplo
            setTimeout(() => {
                setStats({
                    usuarios: 154,
                    deportistas: 89,
                    ingresos: '$12,450',
                    actividades: 23
                });
            }, 1000);
        } catch (err) {
            console.error('Error cargando estadísticas:', err);
        }
    };

    const handleLogout = async () => {
        try {
            const token = localStorage.getItem('token');
            await axios.post(
                'http://localhost:8000/api/auth/logout',
                {},
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        Accept: 'application/json',
                    },
                }
            );
        } catch (e) {
            // no importa si falla
        } finally {
            localStorage.removeItem('token');
            localStorage.removeItem('user');
            window.location.href = '/login';
        }
    };

    if (loading) return (
        <div className="loading">
            Cargando dashboard...
        </div>
    );
    
    if (error) return (
        <div className="error">
            <XCircle size={48} className="mx-auto mb-4" />
            {error}
        </div>
    );
    
    if (!user) return null;

    return (
        <div className="admin-layout fade-in">
            {/* SIDEBAR */}
            <Sidebar />

            {/* CONTENIDO PRINCIPAL */}
            <div className="admin-main">
                
                

                {/* CONTENIDO */}
                <main className="content">
                    <Topbar />
                    {/* BIENVENIDA */}
                    <section className="welcome">
                        <h3>¡Bienvenido de nuevo, {user.nombre}!</h3>
                        <p>Panel de administración del sistema - Gestión integral</p>
                    </section>

                    {/* ESTADÍSTICAS */}
                    <section className="stats">
                        <div className="stat-card">
                            <h4>
                                <Users size={16} className="inline mr-2" />
                                Usuarios
                            </h4>
                            <span>{stats.usuarios}</span>
                            <p className="text-sm text-gray-500 mt-2">Total registrados</p>
                        </div>
                        <div className="stat-card">
                            <h4>
                                <UserIcon size={16} className="inline mr-2" />
                                Deportistas
                            </h4>
                            <span>{stats.deportistas}</span>
                            <p className="text-sm text-gray-500 mt-2">Activos</p>
                        </div>
                        <div className="stat-card">
                            <h4>
                                <TrendingUp size={16} className="inline mr-2" />
                                Ingresos
                            </h4>
                            <span>{stats.ingresos}</span>
                            <p className="text-sm text-gray-500 mt-2">Este mes</p>
                        </div>
                        <div className="stat-card">
                            <h4>
                                <Activity size={16} className="inline mr-2" />
                                Actividades
                            </h4>
                            <span>{stats.actividades}</span>
                            <p className="text-sm text-gray-500 mt-2">Programadas</p>
                        </div>
                    </section>

                    {/* INFORMACIÓN PERSONAL */}
                    <section className="panel">
                        <h4>Información personal</h4>
                        <div className="info-grid">
                            <InfoItem 
                                icon={<Mail size={18} />} 
                                label="Email" 
                                value={user.email} 
                            />
                            <InfoItem 
                                icon={<Phone size={18} />} 
                                label="Teléfono" 
                                value={user.telefono || 'No especificado'} 
                            />
                            <InfoItem 
                                icon={<MapPin size={18} />} 
                                label="Dirección" 
                                value={user.direccion || 'No especificada'} 
                            />
                            <InfoItem 
                                icon={<Shield size={18} />} 
                                label="Rol" 
                                value={user.rol?.nombre || 'Sin rol'} 
                            />
                            <InfoItem 
                                icon={user.status === 'activo' ? <CheckCircle size={18} /> : <XCircle size={18} />} 
                                label="Estado" 
                                value={
                                    <span className={`status-badge ${user.status === 'activo' ? 'status-active' : 'status-inactive'}`}>
                                        {user.status === 'activo' ? 'Activo' : 'Inactivo'}
                                    </span>
                                } 
                            />
                        </div>
                    </section>

                    {/* ACCIONES RÁPIDAS */}
                    <section className="panel">
                        <h4>Acciones rápidas</h4>
                        <div className="actions">
                            <ActionButton 
                                icon={<UserIcon size={20} />} 
                                label="Mi perfil" 
                                onClick={() => alert('Ir a mi perfil')} 
                            />
                            <ActionButton 
                                icon={<Settings size={20} />} 
                                label="Configuraciones" 
                                onClick={() => alert('Ir a configuraciones')} 
                            />
                            <ActionButton 
                                icon={<HelpCircle size={20} />} 
                                label="Ayuda" 
                                onClick={() => alert('Ir a ayuda')} 
                            />
                        </div>
                    </section>

                </main>
            </div>
        </div>
    );
}

/* ================= COMPONENTES AUXILIARES ================= */

function InfoItem({ icon, label, value }) {
    return (
        <div className="info-item slide-in">
            <div className="flex items-center gap-2 mb-1">
                {icon}
                <span>{label}:</span>
            </div>
            <strong>{value}</strong>
        </div>
    );
}

function ActionButton({ icon, label, onClick }) {
    return (
        <button className="action-btn transition-all shadow-hover" onClick={onClick}>
            {icon}
            {label}
        </button>
    );
}