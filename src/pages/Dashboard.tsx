import { Row, Col, Avatar, Tag, Tooltip, Progress, Button } from 'antd';
import Sidebar from '../components/Sidebar';
import HeaderBar from '../components/HeaderBar';
import {
  UserOutlined,
  ProjectOutlined,
  CheckCircleOutlined,
  ArrowUpOutlined,
  ArrowDownOutlined,
  ExclamationCircleOutlined,
} from '@ant-design/icons';
import { useTranslation } from 'react-i18next';

const stats = [
  {
    title: 'Tổng nhân viên',
    value: 124,
    change: '+5.4%',
    icon: <UserOutlined />, 
    trend: 'up',
    color: 'linear-gradient(135deg, #4B48E5 60%, #00d4ff 100%)',
  },
  {
    title: 'Dự án đang tiến hành',
    value: 18,
    change: '+2.2%',
    icon: <ProjectOutlined />, 
    trend: 'up',
    color: 'linear-gradient(135deg, #00d4ff 60%, #4B48E5 100%)',
  },
  {
    title: 'Nhiệm vụ hôm nay',
    value: 24,
    change: '+4.3%',
    icon: <CheckCircleOutlined />, 
    trend: 'up',
    color: 'linear-gradient(135deg, #4BE5B7 60%, #4B48E5 100%)',
  },
  {
    title: 'Tỷ lệ hoàn thành',
    value: '78%',
    change: '+3.8%',
    icon: <CheckCircleOutlined />, 
    trend: 'up',
    color: 'linear-gradient(135deg, #ffb347 60%, #4B48E5 100%)',
    progress: 78,
  },
];

const tasks = [
  {
    id: 1,
    name: 'Thiết kế UI Dashboard',
    status: 'Đang làm',
    deadline: '2024-06-10',
    assignee: 'Nguyễn Văn A',
    avatar: '',
    priority: 'Cao',
  },
  {
    id: 2,
    name: 'Tích hợp API dự án',
    status: 'Chờ duyệt',
    deadline: '2024-06-12',
    assignee: 'Trần Thị B',
    avatar: '',
    priority: 'Trung bình',
  },
  {
    id: 3,
    name: 'Viết tài liệu hướng dẫn',
    status: 'Hoàn thành',
    deadline: '2024-06-08',
    assignee: 'Lê Văn C',
    avatar: '',
    priority: 'Thấp',
  },
  {
    id: 4,
    name: 'Kiểm thử chức năng mới',
    status: 'Đang làm',
    deadline: '2024-06-11',
    assignee: 'Phạm D',
    avatar: '',
    priority: 'Cao',
  },
];

export default function Dashboard() {
  const { t } = useTranslation();
  return (
    <Row style={{ minHeight: '100vh', background: 'linear-gradient(120deg, #f8fafd 60%, #e6eaff 100%)' }}>
      <Col>
        <Sidebar />
      </Col>
      <Col flex="auto" style={{ display:'flex', flexDirection:'column' }}>
        <HeaderBar />
        <div style={{ padding:32, flex:1, overflow:'auto' }}>
          {/* Stats */}
          <Row gutter={[24, 24]}>
            {stats.map((stat, idx) => (
              <Col xs={24} sm={12} lg={6} key={stat.title}>
                <div style={{
                  background:'#fff',
                  borderRadius: 20,
                  boxShadow: '0 4px 24px #4B48E510',
                  padding: 24,
                  display: 'flex',
                  flexDirection: 'column',
                  alignItems: 'flex-start',
                  minHeight: 140,
                  position: 'relative',
                  transition: 'box-shadow 0.2s',
                }}>
                  <div style={{
                    width: 48, height: 48, borderRadius: '50%',
                    background: stat.color,
                    display: 'flex', alignItems: 'center', justifyContent: 'center',
                    marginBottom: 18,
                    boxShadow: '0 2px 8px #4B48E540',
                  }}>
                    {stat.icon}
                  </div>
                  <div style={{ fontSize: 28, fontWeight: 900, color: '#222', marginBottom: 4 }}>{stat.value}</div>
                  <div style={{ fontSize: 15, color: '#888', fontWeight: 600 }}>{stat.title}</div>
                  <div style={{ position: 'absolute', top: 24, right: 24, display: 'flex', alignItems: 'center', fontSize: 15, fontWeight: 700, color: stat.trend === 'up' ? '#4BE5B7' : '#fa3e3e' }}>
                    {stat.trend === 'up' ? <ArrowUpOutlined /> : <ArrowDownOutlined />} {stat.change}
                  </div>
                  {stat.progress !== undefined && (
                    <div style={{ width: '100%', marginTop: 12 }}>
                      <Progress percent={stat.progress} size="small" showInfo={false} strokeColor="#4B48E5" />
                    </div>
                  )}
                </div>
              </Col>
            ))}
          </Row>

          {/* Task List */}
          <Row gutter={[24, 24]} style={{ marginTop: 32 }}>
            <Col xs={24} lg={16}>
              <div style={{background:'#fff', borderRadius:20, boxShadow:'0 2px 12px #4B48E510', padding:24, minHeight:420, display:'flex', flexDirection:'column'}}>
                <div style={{fontWeight:700, fontSize:20, marginBottom:18, display:'flex', alignItems:'center', gap:10}}>
                  <ExclamationCircleOutlined style={{color:'#4B48E5', fontSize:22}} /> {t('tasks')}
                  <Button type="primary" size="small" style={{marginLeft:'auto', borderRadius: 16}}>{t('createNew')}</Button>
                </div>
                <div style={{display:'flex', flexDirection:'column', gap:18}}>
                  {tasks.map(task => (
                    <div key={task.id} style={{
                      background:'#f8fafd',
                      borderRadius:14,
                      boxShadow:'0 2px 8px #4B48E505',
                      padding:'18px 20px',
                      display:'flex',
                      alignItems:'center',
                      gap:18,
                      borderLeft: task.priority === 'Cao' ? '4px solid #fa3e3e' : task.priority === 'Trung bình' ? '4px solid #faad14' : '4px solid #4BE5B7',
                      transition:'box-shadow 0.2s',
                    }}>
                      <div style={{flex:1}}>
                        <div style={{fontWeight:800, fontSize:17, color:'#222', marginBottom:2}}>{task.name}</div>
                        <div style={{display:'flex', alignItems:'center', gap:12, fontSize:14, color:'#888'}}>
                          <Tag color={task.status === 'Hoàn thành' ? 'green' : task.status === 'Đang làm' ? 'blue' : 'orange'} style={{margin:0}}>{task.status}</Tag>
                          <span>Hạn: <b>{task.deadline}</b></span>
                          <span>Ưu tiên: <Tag color={task.priority === 'Cao' ? 'red' : task.priority === 'Trung bình' ? 'gold' : 'green'} style={{margin:0}}>{task.priority}</Tag></span>
                        </div>
                      </div>
                      <Tooltip title={task.assignee}>
                        <Avatar style={{background:'#e0e0e0', color:'#555'}} icon={<UserOutlined />} />
                      </Tooltip>
                    </div>
                  ))}
                </div>
              </div>
            </Col>
            <Col xs={24} lg={8}>
              {/* Có thể thêm block khác ở đây nếu muốn */}
            </Col>
          </Row>
        </div>
      </Col>
    </Row>
  );
}
