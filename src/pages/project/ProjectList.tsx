import React from 'react';
import { Layout, Button, Table, Tag, Avatar, Progress, Modal, Form, Input, DatePicker, Select, Upload } from 'antd';
import { PlusOutlined, EditOutlined, DeleteOutlined } from '@ant-design/icons';
import Sidebar from '../../components/Sidebar';
import HeaderBar from '../../components/HeaderBar';
import { useTranslation } from 'react-i18next';
import { useNavigate } from 'react-router-dom';
import { UploadOutlined } from '@ant-design/icons';
import type { Dayjs } from 'dayjs';
import { useSelector, useDispatch } from 'react-redux';
import type { AppDispatch, RootState } from '../../app/store';
import { useEffect } from 'react';
import { getProjects } from '../../features/project/projectSlice';
import { Spin } from 'antd';

const { Content } = Layout;
const { RangePicker } = DatePicker;

const statusOptions = [
  { value: 'processing', label: 'Đang thực hiện' },
  { value: 'pending', label: 'Chờ duyệt' },
  { value: 'done', label: 'Hoàn thành' },
];
const priorityOptions = [
  { value: 'high', label: 'Cao' },
  { value: 'medium', label: 'Trung bình' },
  { value: 'low', label: 'Thấp' },
];
const memberOptions = [
  { value: 'Nguyễn Văn A', label: 'Nguyễn Văn A' },
  { value: 'Trần Thị B', label: 'Trần Thị B' },
  { value: 'Lê Văn C', label: 'Lê Văn C' },
  { value: 'Phạm D', label: 'Phạm D' },
];
const roleOptions = [
  { value: 'admin', label: 'Quản trị viên' },
  { value: 'member', label: 'Thành viên' },
];

const ProjectList: React.FC = () => {
  const { t } = useTranslation();
  const navigate = useNavigate();
  const [modalOpen, setModalOpen] = React.useState(false);
  const [form] = Form.useForm();
  const [selectedMembers, setSelectedMembers] = React.useState<string[]>([]);
  const [memberRoles, setMemberRoles] = React.useState<{[key:string]: string}>({});
  const [fileList, setFileList] = React.useState<any[]>([]);
  const [dateRange, setDateRange] = React.useState<[Dayjs | null, Dayjs | null]>([null, null]);
  const [memberSalaries, setMemberSalaries] = React.useState<{[key:string]: number}>({});
  const projects = useSelector((state: RootState) => state.project.list);
  const loading = useSelector((state: RootState) => state.project.loading);
  const dispatch = useDispatch<AppDispatch>();

  useEffect(() => {
    dispatch(getProjects());
  }, [dispatch]);

  // Sample data for the table
  const data = [
    {
      key: '1',
      name: 'Website Redesign',
      category: 'Marketing',
      status: 'Active',
      dueDate: '2023-06-15',
      team: [
        'https://storage.googleapis.com/a1aa/image/d41ce88f-8cae-4c7f-0117-2da7211c7699.jpg',
        'https://storage.googleapis.com/a1aa/image/e9962697-c2be-45fb-cae6-9453bc772535.jpg',
      ],
      tasks: 12,
      progress: 65,
    },
    {
      key: '2',
      name: 'Mobile App Development',
      category: 'Development',
      status: 'Pending',
      dueDate: '2023-07-01',
      team: [
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
      ],
      tasks: 8,
      progress: 30,
    },
    {
      key: '3',
      name: 'CRM Integration',
      category: 'Business',
      status: 'Active',
      dueDate: '2023-07-20',
      team: [
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
      ],
      tasks: 15,
      progress: 80,
    },
    {
      key: '4',
      name: 'SEO Optimization',
      category: 'Marketing',
      status: 'Pending',
      dueDate: '2023-08-01',
      team: [
        'https://placehold.co/40x40',
      ],
      tasks: 5,
      progress: 20,
    },
    {
      key: '5',
      name: 'Cloud Migration',
      category: 'IT',
      status: 'Active',
      dueDate: '2023-08-15',
      team: [
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
      ],
      tasks: 20,
      progress: 55,
    },
    {
      key: '6',
      name: 'Brand Refresh',
      category: 'Design',
      status: 'Active',
      dueDate: '2023-09-01',
      team: [
        'https://placehold.co/40x40',
      ],
      tasks: 7,
      progress: 40,
    },
    {
      key: '7',
      name: 'Security Audit',
      category: 'IT',
      status: 'Pending',
      dueDate: '2023-09-10',
      team: [
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
      ],
      tasks: 9,
      progress: 10,
    },
    {
      key: '8',
      name: 'Content Strategy',
      category: 'Marketing',
      status: 'Active',
      dueDate: '2023-09-20',
      team: [
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
      ],
      tasks: 11,
      progress: 60,
    },
    {
      key: '9',
      name: 'Data Analysis',
      category: 'Business',
      status: 'Pending',
      dueDate: '2023-10-01',
      team: [
        'https://placehold.co/40x40',
      ],
      tasks: 6,
      progress: 25,
    },
    {
      key: '10',
      name: 'Customer Survey',
      category: 'Research',
      status: 'Active',
      dueDate: '2023-10-15',
      team: [
        'https://placehold.co/40x40',
        'https://placehold.co/40x40',
      ],
      tasks: 13,
      progress: 70,
    },
  ];

  // Columns for the Ant Design Table
  const columns = [
    {
      title: t('project'),
      dataIndex: 'name',
      key: 'name',
      render: (text: string, record: any) => (
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
          <Avatar style={{ backgroundColor: '#87d068' }} icon={<PlusOutlined />} />
          <div>
            <strong>{text}</strong>
            <br />
            <small style={{ color: '#888' }}>{record.category}</small>
          </div>
        </div>
      ),
    },
    {
      title: t('status'),
      dataIndex: 'status',
      key: 'status',
      render: (status: string) => {
        const color = status === 'Active' ? 'green' : 'orange';
        return <Tag color={color}>{status}</Tag>;
      },
    },
    {
      title: t('dueDate'),
      dataIndex: 'dueDate',
      key: 'dueDate',
    },
    {
      title: t('team'),
      dataIndex: 'team',
      key: 'team',
      render: (team: string[]) => (
        <span>{t('numberOfMembers', { count: team.length })}</span>
      ),
    },
    {
      title: t('tasks'),
      dataIndex: 'tasks',
      key: 'tasks',
      render: (tasks: number) => <span>{t('numberOfTasks', { count: tasks })}</span>,
    },
    {
      title: t('progress'),
      dataIndex: 'progress',
      key: 'progress',
      render: (progress: number) => (
        <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
          <Progress percent={progress} size="small" />
          <span>{progress}%</span>
        </div>
      ),
    },
    {
      title: t('actions'),
      key: 'actions',
      render: (_: any, record: any) => (
        <div style={{ display: 'flex', gap: '8px' }}>
          <Button type="link" icon={<EditOutlined />} onClick={() => navigate(`/projects/${record.key}`)}>
            {t('edit')}
          </Button>
          <Button type="link" danger icon={<DeleteOutlined />}>
            {t('archive')}
          </Button>
        </div>
      ),
    },
  ];

  // Tính số tháng dự án
  const getMonthDiff = (start: any, end: any) => {
    if (!start || !end) return 0;
    const s = start.clone ? start.clone() : start;
    const e = end.clone ? end.clone() : end;
    return e.diff(s, 'months', true) + 1;
  };
  const numMonths = dateRange[0] && dateRange[1] ? getMonthDiff(dateRange[0], dateRange[1]) : 0;
  const totalSalary = selectedMembers.reduce((sum, m) => sum + (memberSalaries[m] || 0), 0) * numMonths;
  const budget = form.getFieldValue('budget') || 0;
  const profit = budget - totalSalary;

  const defaultSalaries: { [key: string]: number } = {
    'Nguyễn Văn A': 20000000,
    'Trần Thị B': 15000000,
    'Lê Văn C': 18000000,
    'Phạm D': 12000000,
  };

  const tableData = projects && projects.length > 0 ? projects : data;

  return (
    <Layout style={{ minHeight: '100vh' }}>
      <Sidebar />
      <Layout>
        <HeaderBar />
        <Content style={{ margin: '24px', padding: '24px', background: '#fff', borderRadius: '8px' }}>
          <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '16px' }}>
            <h2 style={{ margin: 0 }}>{t('projects')}</h2>
            <Button type="primary" icon={<PlusOutlined />} onClick={() => setModalOpen(true)}>
              {t('createNew')}
            </Button>
          </div>
          {loading ? (
            <Spin />
          ) : (
            <Table columns={columns} dataSource={tableData}
              pagination={{
                pageSize: 5,
                showSizeChanger: true,
                pageSizeOptions: [5, 10, 20],
                showTotal: (total: number, range: [number, number]) => `${range[0]}-${range[1]} / ${total} projects`
              }}
            />
          )}
          <Modal
            title={t('createNew') + ' dự án'}
            open={modalOpen}
            onCancel={() => setModalOpen(false)}
            onOk={() => {
              form.validateFields().then((values: any) => {
                setModalOpen(false);
                form.resetFields();
                setSelectedMembers([]);
                setMemberRoles({});
                setFileList([]);
                setDateRange([null, null]);
                setMemberSalaries({});
                console.log('Tạo mới dự án:', {
                  ...values,
                  members: selectedMembers.map(m => ({ name: m, role: memberRoles[m] || 'member', salary: memberSalaries[m] || 0 })),
                  files: fileList,
                  dateRange,
                  totalSalary,
                  profit,
                });
              });
            }}
            okText={t('createNew')}
            cancelText={t('cancel')}
            width={900}
          >
            <Form
              form={form}
              layout="vertical"
              initialValues={{ status: 'processing', priority: 'medium' }}
            >
              <Form.Item name="name" label={t('projectName')} rules={[{ required: true, message: t('projectName') + ' ' + t('required') }]}> <Input placeholder={t('projectName')} /> </Form.Item>
              <Form.Item name="description" label={t('description')}> <Input.TextArea rows={2} placeholder={t('description')} /> </Form.Item>
              <Form.Item name="manager" label={t('manager')} rules={[{ required: true, message: t('selectManager') }]}> 
                <Select
                  placeholder={t('selectManager')}
                  options={selectedMembers.map(m => ({ value: m, label: m }))}
                  disabled={selectedMembers.length === 0}
                  showSearch
                  filterOption={(input, option) => (option?.label ?? '').toLowerCase().includes(input.toLowerCase())}
                />
              </Form.Item>
              <Form.Item label={t('projectTime')} required>
                <RangePicker
                  style={{ width: '100%' }}
                  value={dateRange}
                  onChange={v => setDateRange(v ? v as [Dayjs, Dayjs] : [null, null])}
                  format="DD/MM/YYYY"
                />
              </Form.Item>
              <Form.Item name="budget" label={t('budget')} rules={[{ required: true, message: t('budget') + ' ' + t('required') }]}> <Input type="number" min={0} addonAfter="VNĐ" placeholder={t('budget')} /> </Form.Item>
              {/* Hiển thị tổng lương và lợi nhuận */}
              <div style={{ margin: '12px 0 0 0', fontWeight: 600, color: '#4B48E5' }}>
                {t('totalSalary')}: {totalSalary.toLocaleString()} VNĐ<br />
                {t('profit')}: <span style={{ color: profit >= 0 ? '#52c41a' : '#fa3e3e' }}>{profit.toLocaleString()} VNĐ</span>
              </div>
              <Form.Item name="status" label={t('status')}> <Select options={statusOptions} /> </Form.Item>
              <Form.Item name="priority" label={t('priority')}> <Select options={priorityOptions} /> </Form.Item>
              <Form.Item label={t('uploadHint')}>
                <Upload.Dragger
                  multiple
                  fileList={fileList}
                  beforeUpload={() => false}
                  onChange={({ fileList }) => setFileList(fileList)}
                  onRemove={file => {
                    setFileList(prev => prev.filter(f => f.uid !== file.uid));
                    return true;
                  }}
                >
                  <p className="ant-upload-drag-icon"><UploadOutlined /></p>
                  <p className="ant-upload-text">{t('uploadHint')}</p>
                </Upload.Dragger>
              </Form.Item>
              <Form.Item label={t('selectMembers')}>
                <Select
                  mode="multiple"
                  placeholder={t('selectMembers')}
                  options={memberOptions}
                  value={selectedMembers}
                  onChange={members => {
                    setSelectedMembers(members);
                    setMemberRoles(prev => {
                      const next: {[key:string]: string} = {};
                      members.forEach(m => { next[m] = prev[m] || 'member'; });
                      return next;
                    });
                    setMemberSalaries(prev => {
                      const next: {[key:string]: number} = {};
                      members.forEach(m => {
                        next[m] = prev[m] !== undefined ? prev[m] : (defaultSalaries[m] || 0);
                      });
                      return next;
                    });
                  }}
                  style={{ width: '100%' }}
                />
                {/* Danh sách thành viên đã chọn, phân quyền, nhập lương */}
                {selectedMembers.length > 0 && (
                  <div style={{ marginTop: 12 }}>
                    {selectedMembers.map(m => (
                      <div key={m} style={{ display: 'flex', alignItems: 'center', gap: 8, marginBottom: 6 }}>
                        <span style={{ minWidth: 120 }}>{m}</span>
                        <Select
                          value={memberRoles[m] || 'member'}
                          options={roleOptions}
                          style={{ width: 140 }}
                          onChange={role => setMemberRoles(r => ({ ...r, [m]: role }))}
                        />
                        <Input
                          type="number"
                          min={0}
                          placeholder={t('salaryPerMonth')}
                          value={memberSalaries[m] || ''}
                          onChange={e => setMemberSalaries(s => ({ ...s, [m]: Number(e.target.value) }))}
                          style={{ width: 180 }}
                          addonAfter="VNĐ"
                        />
                      </div>
                    ))}
                  </div>
                )}
              </Form.Item>
            </Form>
          </Modal>
        </Content>
      </Layout>
    </Layout>
  );
};

export default ProjectList;