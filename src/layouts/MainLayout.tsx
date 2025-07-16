import { Layout } from 'antd';
import Sidebar from '../components/Sidebar';
import HeaderBar from '../components/HeaderBar';

export default function MainLayout({ children }: { children: React.ReactNode }) {
  return (
    <Layout style={{ minHeight: '100vh' }}>
      <Sidebar />
      <Layout>
        <HeaderBar />
        <Layout.Content style={{ padding: 24, background: '#F3F4F6' }}>
          {children}
        </Layout.Content>
      </Layout>
    </Layout>
  );
}
