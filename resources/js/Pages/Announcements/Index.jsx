import React, { useState, useEffect } from 'react';
import { Head, Link } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { usePage } from '@inertiajs/react';
import Pusher from 'pusher-js';

export default function Index({ auth, announcements: initialAnnouncements }) {
    const [announcements, setAnnouncements] = useState(initialAnnouncements);
    const { flash } = usePage().props;
    
    useEffect(() => {
        console.log('🔄 Setting up Pusher connection for real-time announcements...');
        
        // Enable Pusher debugging to help troubleshoot
        Pusher.logToConsole = true;
        
        // Create a new Pusher instance with explicit configuration
        const pusher = new Pusher('a3922529d7935ab4ff01', {
            cluster: 'eu',
            encrypted: true,
            enabledTransports: ['ws', 'wss', 'sockjs'],
            disabledTransports: []
        });
        
        // Subscribe to the announcements channel
        const channel = pusher.subscribe('announcements');
        
        // Debug connection state
        console.log('📡 Current connection state:', pusher.connection.state);
        
        // Function to handle new announcements
        const handleNewAnnouncement = (data) => {
            console.log('📢 New announcement received:', data);
            
            // Use functional update to avoid stale closures
            setAnnouncements(prevAnnouncements => {
                // First, check if we already have this exact announcement
                const existingIndex = prevAnnouncements.findIndex(a => a.id === data.id);
                
                if (existingIndex !== -1) {
                    console.log('🔄 Updating existing announcement');
                    // Update existing announcement
                    const updatedAnnouncements = [...prevAnnouncements];
                    updatedAnnouncements[existingIndex] = data;
                    return updatedAnnouncements;
                } else {
                    console.log('➕ Adding new announcement to the list');
                    // Add new announcement to the beginning of the list
                    return [data, ...prevAnnouncements];
                }
            });
        };
        
        // Bind to the new-announcement event
        channel.bind('new-announcement', handleNewAnnouncement);
        
        // Connection event handlers for debugging
        pusher.connection.bind('connecting', () => {
            console.log('🔄 Connecting to Pusher...');
        });
        
        pusher.connection.bind('connected', () => {
            console.log('✅ Connected to Pusher!');
        });
        
        pusher.connection.bind('unavailable', () => {
            console.log('⚠️ Pusher connection unavailable');
        });
        
        pusher.connection.bind('failed', () => {
            console.log('❌ Pusher connection failed');
        });
        
        pusher.connection.bind('error', (err) => {
            console.error('❌ Pusher connection error:', err);
        });
        
        // Channel event handlers
        channel.bind('subscription_succeeded', () => {
            console.log('✅ Successfully subscribed to announcements channel!');
        });
        
        channel.bind('subscription_error', (error) => {
            console.error('❌ Channel subscription error:', error);
        });
        
        // Cleanup function
        return () => {
            console.log('🧹 Cleaning up Pusher resources...');
            channel.unbind_all();
            pusher.unsubscribe('announcements');
            pusher.disconnect();
        };
    }, []);

    return (
        <AuthenticatedLayout
            user={auth.user}
            header={<h2 className="font-semibold text-xl text-gray-800 leading-tight">Announcements</h2>}
        >
            <Head title="Announcements" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {flash.success && (
                        <div className="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {flash.success}
                        </div>
                    )}
                    
                    {flash.error && (
                        <div className="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {flash.error}
                        </div>
                    )}
                    
                    {auth.user.is_admin && (
                        <div className="mb-6">
                            <Link
                                href={route('announcements.create')}
                                className="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                            >
                                Create New Announcement
                            </Link>
                        </div>
                    )}

                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 text-gray-900">
                            {announcements.length === 0 ? (
                                <p>No announcements available.</p>
                            ) : (
                                <div className="space-y-6">
                                    {announcements.map((announcement) => (
                                        <div key={announcement.id} className="border-b pb-4 last:border-b-0">
                                            <h3 className="text-xl font-bold mb-2">{announcement.title}</h3>
                                            <p className="mb-2">{announcement.content}</p>
                                            <div className="text-sm text-gray-500">
                                                Posted by {announcement.user.name} on {new Date(announcement.created_at).toLocaleString()}
                                            </div>
                                            
                                            
                                        </div>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
