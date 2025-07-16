import { Routes, Route } from 'react-router-dom';
import Dashboard from '../pages/Dashboard';
import ProjectList from '../pages/project/ProjectList';
import ProjectDetail from '../pages/project/ProjectDetail';
import TaskDetail from '../pages/project/TaskDetail';
import TaskKanban from '../pages/project/TaskKaban';
import Login from '../pages/auth/Login';
import ChatList from '../pages/chat/ChatList';

export default function AppRoutes() {
  return (
    <Routes>
      <Route path="/login" element={<Login />} />
      <Route path="/" element={<Dashboard />} />
      <Route path="/projects" element={<ProjectList />} />
      <Route path="/projects/:id" element={<ProjectDetail />} />
      <Route path="/projects/task/:id" element={<TaskDetail />} />
      <Route path="/projects/task/:id/kaban" element={<TaskKanban />} />
      <Route path="/chat" element={<ChatList />} />
      {/* Thêm route khác ở đây */}
    </Routes>
  );
}