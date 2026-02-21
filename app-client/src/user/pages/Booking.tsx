import { useEffect, useState } from "react";
import { useParams, useNavigate } from "react-router-dom";
import { useUserAuth } from "../context/user-auth/useUserAuth";
import apis, { endpoints, userAuthApis } from "../../shared/api/apis";

export default function Booking() {
    const { roomId } = useParams();
    const navigate = useNavigate();
    const { state } = useUserAuth();

    // Redirect nếu chưa login
    useEffect(() => {
        if (!state.isAuthenticated) {
            navigate(`/login?next=${encodeURIComponent(location.pathname)}`);
        }
    }, [state.isAuthenticated, navigate]);

    const [room, setRoom] = useState<any | null>(null);
    const [selectedImage, setSelectedImage] = useState("");
    const [loadingRoom, setLoadingRoom] = useState(true);

    const [form, setForm] = useState({
        checkin_date: "",
        checkout_date: "",
    });

    const [loadingBooking, setLoadingBooking] = useState(false);
    const [error, setError] = useState("");

    // =============================
    // Load room detail
    // =============================
    useEffect(() => {
        if (!roomId) return;

        const loadRoom = async () => {
            try {
                const res = await userAuthApis.get(
                    endpoints["room-detail"](Number(roomId))
                );

                const data = res.data.data;
                setRoom(data);
                setSelectedImage(data.images[0]?.url);
            } catch (err) {
                console.error(err);
            } finally {
                setLoadingRoom(false);
            }
        };

        loadRoom();
    }, [roomId]);

    // =============================
    // Handle form change
    // =============================
    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        setForm({
            ...form,
            [e.target.name]: e.target.value,
        });
    };

    // =============================
    // Handle booking
    // =============================
    const handleBooking = async (e: React.FormEvent) => {
        e.preventDefault();

        if (!state.isAuthenticated) {
            navigate(`/login?next=${encodeURIComponent(location.pathname)}`);
            return;
        }

        setLoadingBooking(true);
        setError("");

        try {
            await userAuthApis.post(endpoints["bookings"], {
                room_id: room?.id,
                checkin_date: form.checkin_date,
                checkout_date: form.checkout_date,
            });

            alert("Đặt phòng thành công");
            navigate("/my-bookings"); // optional redirect
        } catch (err: any) {
            setError(
                err.response?.data?.message ||
                "Đặt phòng thất bại"
            );
        } finally {
            setLoadingBooking(false);
        }
    };

    // =============================
    // Render
    // =============================
    if (loadingRoom) return <div>Loading room...</div>;
    if (!room) return <div>Room not found</div>;

    return (
        <div className="min-h-screen bg-gray-50 py-10">
            <div className="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10">

                {/* ===== LEFT: ROOM GALLERY ===== */}
                <div>
                    {/* Ảnh lớn */}
                    <div className="overflow-hidden rounded-2xl shadow-lg">
                        <img
                            src={selectedImage}
                            alt="Room"
                            className="w-full h-[420px] object-cover hover:scale-105 transition duration-500"
                        />
                    </div>

                    {/* Thumbnail */}
                    <div className="flex gap-3 mt-4">
                        {room.images.map((img: any) => (
                            <img
                                key={img.id}
                                src={img.url}
                                alt="Thumbnail"
                                onClick={() => setSelectedImage(img.url)}
                                className={`w-24 h-24 object-cover cursor-pointer rounded-xl transition border-2
                ${selectedImage === img.url
                                        ? "border-blue-600 scale-105"
                                        : "border-transparent opacity-70 hover:opacity-100"
                                    }`}
                            />
                        ))}
                    </div>
                </div>

                {/* ===== RIGHT: INFO + BOOKING ===== */}
                <div className="space-y-6">

                    {/* Room Info Card */}
                    <div className="bg-white p-6 rounded-2xl shadow-md">
                        <h2 className="text-3xl font-bold text-gray-800 mb-2">
                            {room.room_type}
                        </h2>

                        <p className="text-2xl font-semibold text-blue-600">
                            {room.base_price.toLocaleString()} VND
                            <span className="text-gray-500 text-base font-normal">
                                {" "} / đêm
                            </span>
                        </p>
                    </div>

                    {/* Booking Form Card */}
                    <form
                        onSubmit={handleBooking}
                        className="bg-white p-6 rounded-2xl shadow-md space-y-5"
                    >
                        <h3 className="text-xl font-semibold text-gray-800">
                            Đặt phòng
                        </h3>

                        <div className="space-y-4">
                            <div>
                                <label className="block text-sm text-gray-600 mb-1">
                                    Check-in
                                </label>
                                <input
                                    type="date"
                                    name="checkin_date"
                                    value={form.checkin_date}
                                    onChange={handleChange}
                                    required
                                    className="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                                />
                            </div>

                            <div>
                                <label className="block text-sm text-gray-600 mb-1">
                                    Check-out
                                </label>
                                <input
                                    type="date"
                                    name="checkout_date"
                                    value={form.checkout_date}
                                    onChange={handleChange}
                                    required
                                    className="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none transition"
                                />
                            </div>
                        </div>

                        {error && (
                            <div className="bg-red-50 text-red-600 text-sm p-3 rounded-lg">
                                {error}
                            </div>
                        )}

                        <button
                            type="submit"
                            disabled={loadingBooking}
                            className={`w-full py-3 rounded-xl text-white font-medium transition duration-300 ${loadingBooking
                                    ? "bg-gray-400 cursor-not-allowed"
                                    : "bg-blue-600 hover:bg-blue-700 shadow-md hover:shadow-lg"
                                }`}
                        >
                            {loadingBooking ? "Đang xử lý..." : "Xác nhận đặt phòng"}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    );
}