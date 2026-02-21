import { NavLink, Link, useNavigate } from "react-router-dom";
import { useState, useRef, useEffect } from "react";
import { useUserAuth } from "../context/user-auth/useUserAuth";

const UserHeader = () => {
    const { state, logout } = useUserAuth();
    const navigate = useNavigate();
    const [open, setOpen] = useState(false);
    const dropdownRef = useRef<HTMLDivElement>(null);

    const handleLogout = () => {
        logout();
        navigate("/");
    };

    // 👉 Click outside để đóng dropdown
    useEffect(() => {
        const handleClickOutside = (event: MouseEvent) => {
            if (
                dropdownRef.current &&
                !dropdownRef.current.contains(event.target as Node)
            ) {
                setOpen(false);
            }
        };

        document.addEventListener("mousedown", handleClickOutside);
        return () =>
            document.removeEventListener("mousedown", handleClickOutside);
    }, []);

    const navClass = ({ isActive }: { isActive: boolean }) =>
        isActive
            ? "text-blue-600 font-semibold"
            : "hover:text-blue-600 transition";

    return (
        <header className="bg-white shadow-sm sticky top-0 z-50">
            <div className="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

                {/* Logo */}
                <Link
                    to="/home"
                    className="text-xl font-bold text-blue-600"
                >
                    HotelBooking
                </Link>

                <nav className="flex items-center gap-6 text-sm text-gray-600">

                    <NavLink to="/" className={navClass}>
                        Trang chủ
                    </NavLink>

                    <NavLink to="/hotels" className={navClass}>
                        Khách sạn
                    </NavLink>

                    <NavLink to="/bookings" className={navClass}>
                        Đặt phòng
                    </NavLink>

                    {/* Nếu chưa login */}
                    {!state.isAuthenticated && (
                        <>
                            <Link
                                to="/login"
                                className="text-blue-600 font-medium hover:opacity-80"
                            >
                                Đăng nhập
                            </Link>

                            <Link
                                to="/register"
                                className="bg-blue-600 text-white px-4 py-1.5 rounded-lg hover:bg-blue-700 transition"
                            >
                                Đăng kí
                            </Link>
                        </>
                    )}

                    {/* Nếu đã login */}
                    {state.isAuthenticated && (
                        <div
                            className="relative"
                            ref={dropdownRef}
                        >
                            <button
                                onClick={() => setOpen(!open)}
                                className="flex items-center gap-2 focus:outline-none"
                            >
                                <img
                                    src={
                                        state.user?.avatar_url ||
                                        `https://ui-avatars.com/api/?name=${state.user?.name}`
                                    }
                                    alt="avatar"
                                    className="w-9 h-9 rounded-full object-cover border"
                                />

                                <span className="hidden md:block font-medium">
                                    {state.user?.name}
                                </span>
                            </button>

                            {/* Dropdown */}
                            <div
                                className={`absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border text-sm transition-all duration-200 ${
                                    open
                                        ? "opacity-100 translate-y-0 visible"
                                        : "opacity-0 -translate-y-2 invisible"
                                }`}
                            >
                                <Link
                                    to="/profile"
                                    className="block px-4 py-2 hover:bg-gray-100"
                                    onClick={() => setOpen(false)}
                                >
                                    Thông tin cá nhân
                                </Link>

                                {/* Nếu là manager */}
                                {state.user?.role === "manager" && (
                                    <Link
                                        to="/manager/dashboard"
                                        className="block px-4 py-2 hover:bg-gray-100"
                                        onClick={() => setOpen(false)}
                                    >
                                        Dashboard
                                    </Link>
                                )}

                                <hr className="border-gray-200" />

                                <button
                                    onClick={handleLogout}
                                    className="w-full text-left px-4 py-2 hover:bg-gray-100 text-red-600"
                                >
                                    Đăng xuất
                                </button>
                            </div>
                        </div>
                    )}
                </nav>
            </div>
        </header>
    );
};

export default UserHeader;
